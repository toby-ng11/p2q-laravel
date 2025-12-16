import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    ColumnDef,
    ColumnFiltersState,
    flexRender,
    getCoreRowModel,
    getFacetedUniqueValues,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    PaginationState,
    SortingState,
    useReactTable,
    VisibilityState,
} from '@tanstack/react-table';
import axios from 'axios';
import {
    ChevronLeft,
    ChevronRight,
    ChevronsLeft,
    ChevronsRight,
    PlusCircle,
    Settings2,
} from 'lucide-react';
import { useEffect, useRef, useState } from 'react';
import {
    DataTableFacetedFilter,
    getColumnOptions,
} from './table-faceted-filter';
import { DataTableSkeleton } from './table-skeleton';
import { Popover, PopoverContent, PopoverTrigger } from './ui/popover';

interface FacetedFilterConfig {
    columnId: string;
    title: string;
}

interface DataTableProps<TData, TValue> {
    columns: ColumnDef<TData, TValue>[];
    data: TData[];
    paginationState?: PaginationState;
    sortingColumns?: { id: string; desc: boolean }[];
    hasPagination?: boolean;
    hasSearch?: { searchColumn: string; searchPlaceholder?: string };
    hasVisibilityControl?: { retriveEndpoint?: string; saveEndpoint?: string };
    hasFacetedFilter?: { facetedFilters?: FacetedFilterConfig[] };
    hasSelect?: boolean;
    isFetching?: boolean;
}

export function DataTable<TData, TValue>({
    columns,
    data,
    paginationState = { pageIndex: 0, pageSize: 10 },
    sortingColumns = [{ id: 'id', desc: true }],
    hasPagination = false,
    hasSearch = { searchColumn: 'name', searchPlaceholder: 'Search users...' },
    hasVisibilityControl = {
        retriveEndpoint: '/endpoint',
        saveEndpoint: '/endpoint',
    },
    hasFacetedFilter = { facetedFilters: [] },
    hasSelect = false,
    isFetching = false,
}: DataTableProps<TData, TValue>) {
    const PaginationRowSelectedNumber = () => (
        <div className="flex-1 text-sm text-muted-foreground">
            {table.getFilteredSelectedRowModel().rows.length} of{' '}
            {table.getFilteredRowModel().rows.length} row(s) selected.
        </div>
    );

    const PaginationPageSize = () => (
        <div className="flex items-center space-x-2">
            <p className="text-sm font-medium">Rows per page</p>
            <Select
                value={`${table.getState().pagination.pageSize}`}
                onValueChange={(value) => {
                    table.setPageSize(Number(value));
                }}
            >
                <SelectTrigger className="h-8 w-17.5">
                    <SelectValue
                        placeholder={table.getState().pagination.pageSize}
                    />
                </SelectTrigger>
                <SelectContent side="top">
                    {[10, 15, 20, 25, 30, 40, 50].map((pageSize) => (
                        <SelectItem key={pageSize} value={`${pageSize}`}>
                            {pageSize}
                        </SelectItem>
                    ))}
                </SelectContent>
            </Select>
        </div>
    );

    const PaginationPageIndex = () => (
        <div className="flex w-25 items-center justify-center text-sm font-medium">
            Page {table.getState().pagination.pageIndex + 1} of{' '}
            {table.getPageCount()}
        </div>
    );

    const PaginationButtonGroup = () => (
        <div className="flex items-center space-x-2">
            <Button
                variant="outline"
                size="icon"
                className="hidden size-8 lg:flex"
                onClick={() => table.setPageIndex(0)}
                disabled={!table.getCanPreviousPage()}
            >
                <span className="sr-only">Go to first page</span>
                <ChevronsLeft />
            </Button>
            <Button
                variant="outline"
                size="icon"
                className="size-8"
                onClick={() => table.previousPage()}
                disabled={!table.getCanPreviousPage()}
            >
                <span className="sr-only">Go to previous page</span>
                <ChevronLeft />
            </Button>
            <Button
                variant="outline"
                size="icon"
                className="size-8"
                onClick={() => table.nextPage()}
                disabled={!table.getCanNextPage()}
            >
                <span className="sr-only">Go to next page</span>
                <ChevronRight />
            </Button>
            <Button
                variant="outline"
                size="icon"
                className="hidden size-8 lg:flex"
                onClick={() => table.setPageIndex(table.getPageCount() - 1)}
                disabled={!table.getCanNextPage()}
            >
                <span className="sr-only">Go to last page</span>
                <ChevronsRight />
            </Button>
        </div>
    );

    const VisibilityOptions = () => (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button
                    variant="outline"
                    size="sm"
                    className="ml-auto hidden h-8 lg:flex"
                >
                    <Settings2 />
                    View
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-fit">
                <DropdownMenuLabel>Toggle columns</DropdownMenuLabel>
                <DropdownMenuSeparator />
                {table
                    .getAllColumns()
                    .filter(
                        (column) =>
                            typeof column.accessorFn !== 'undefined' &&
                            column.getCanHide(),
                    )
                    .map((column) => {
                        return (
                            <DropdownMenuCheckboxItem
                                key={column.id}
                                className="capitalize"
                                checked={column.getIsVisible()}
                                onCheckedChange={(value) =>
                                    column.toggleVisibility(!!value)
                                }
                            >
                                {(column.columnDef.meta as string) || column.id}
                            </DropdownMenuCheckboxItem>
                        );
                    })}
            </DropdownMenuContent>
        </DropdownMenu>
    );

    const [pagination, setPagination] =
        useState<PaginationState>(paginationState);
    const [sorting, setSorting] = useState<SortingState>(sortingColumns);
    const [columnFilters, setColumnFilters] = useState<ColumnFiltersState>([]);
    const [columnVisibility, setColumnVisibility] = useState<VisibilityState>(
        {},
    );
    const lastSavedVisibility = useRef<VisibilityState>({});
    const [isReady, setIsReady] = useState(false); // for visibility

    // Restore saved visibility
    useEffect(() => {
        axios
            .get(hasVisibilityControl.retriveEndpoint || '/endpoint')
            .then((res) => {
                setColumnVisibility(res.data || {});
                lastSavedVisibility.current = res.data;
                setIsReady(true);
            });
    }, [hasVisibilityControl.retriveEndpoint]);

    useEffect(() => {
        if (!isReady) return;

        const current = JSON.stringify(columnVisibility);
        const previous = JSON.stringify(lastSavedVisibility.current);

        if (current !== previous) {
            axios.post(hasVisibilityControl.saveEndpoint || '/endpoint', {
                value: columnVisibility,
            });
            lastSavedVisibility.current = columnVisibility;
        }
    }, [columnVisibility, isReady, hasVisibilityControl.saveEndpoint]);

    // waiting for tanstack to fix this :)
    // eslint-disable-next-line react-hooks/incompatible-library
    const table = useReactTable({
        data,
        columns,
        getCoreRowModel: getCoreRowModel(),
        getPaginationRowModel: getPaginationRowModel(),
        getSortedRowModel: getSortedRowModel(),
        getFilteredRowModel: getFilteredRowModel(),
        getFacetedUniqueValues: getFacetedUniqueValues(),
        onPaginationChange: setPagination,
        onSortingChange: setSorting,
        onColumnFiltersChange: setColumnFilters,
        onColumnVisibilityChange: setColumnVisibility,
        state: {
            pagination,
            sorting,
            columnFilters,
            columnVisibility,
        },
    });

    const searchCol = table.getColumn(hasSearch.searchColumn);

    return isReady && !isFetching ? (
        <div className="flex flex-col gap-4">
            <div className="flex items-center justify-between">
                <div className="flex flex-1 items-center gap-2">
                    {hasSearch && searchCol && (
                        <Input
                            placeholder={hasSearch.searchPlaceholder}
                            value={(searchCol.getFilterValue() as string) ?? ''}
                            onChange={(event) =>
                                searchCol.setFilterValue(event.target.value)
                            }
                            className="h-8 w-37.5 lg:w-62.5"
                        />
                    )}
                    {hasFacetedFilter.facetedFilters !== undefined &&
                        hasFacetedFilter.facetedFilters.length > 0 && (
                            <>
                                <div className="hidden flex-wrap gap-2 lg:flex">
                                    {hasFacetedFilter.facetedFilters.map(
                                        ({ columnId, title }) => {
                                            const col =
                                                table.getColumn(columnId);
                                            return (
                                                col && (
                                                    <DataTableFacetedFilter
                                                        key={columnId}
                                                        column={col}
                                                        title={title}
                                                        options={getColumnOptions(
                                                            col,
                                                        )}
                                                    />
                                                )
                                            );
                                        },
                                    )}
                                </div>

                                <div className="flex lg:hidden">
                                    <Popover>
                                        <PopoverTrigger asChild>
                                            <Button
                                                variant="outline"
                                                size="sm"
                                                className="h-8"
                                            >
                                                <PlusCircle className="size-4" />
                                                Filters
                                            </Button>
                                        </PopoverTrigger>
                                        <PopoverContent
                                            className="w-fit max-w-100 p-4"
                                            align="start"
                                        >
                                            <div className="flex flex-col gap-2">
                                                {hasFacetedFilter.facetedFilters.map(
                                                    ({ columnId, title }) => {
                                                        const col =
                                                            table.getColumn(
                                                                columnId,
                                                            );
                                                        return (
                                                            col && (
                                                                <DataTableFacetedFilter
                                                                    key={
                                                                        columnId
                                                                    }
                                                                    column={col}
                                                                    title={
                                                                        title
                                                                    }
                                                                    options={getColumnOptions(
                                                                        col,
                                                                    )}
                                                                />
                                                            )
                                                        );
                                                    },
                                                )}
                                            </div>
                                        </PopoverContent>
                                    </Popover>
                                </div>
                            </>
                        )}
                </div>

                <div className="flex items-center gap-4">
                    <VisibilityOptions />
                </div>
            </div>

            <div className="overflow-hidden rounded-md border">
                <Table>
                    <TableHeader className="sticky top-0 z-10 bg-muted [&_tr]:border-b">
                        {table.getHeaderGroups().map((headerGroup) => (
                            <TableRow key={headerGroup.id}>
                                {headerGroup.headers.map((header) => {
                                    return (
                                        <TableHead key={header.id}>
                                            {header.isPlaceholder
                                                ? null
                                                : flexRender(
                                                      header.column.columnDef
                                                          .header,
                                                      header.getContext(),
                                                  )}
                                        </TableHead>
                                    );
                                })}
                            </TableRow>
                        ))}
                    </TableHeader>
                    <TableBody>
                        {table.getRowModel().rows?.length ? (
                            table.getRowModel().rows.map((row) => (
                                <TableRow
                                    key={row.id}
                                    data-state={
                                        row.getIsSelected() && 'selected'
                                    }
                                >
                                    {row.getVisibleCells().map((cell) => (
                                        <TableCell key={cell.id}>
                                            {flexRender(
                                                cell.column.columnDef.cell,
                                                cell.getContext(),
                                            )}
                                        </TableCell>
                                    ))}
                                </TableRow>
                            ))
                        ) : (
                            <TableRow>
                                <TableCell
                                    colSpan={columns.length}
                                    className="h-24 text-center"
                                >
                                    No results.
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>
            </div>

            <div className="flex items-center justify-between px-2">
                {hasPagination && (
                    <>
                        {hasSelect ? (
                            <>
                                <PaginationRowSelectedNumber />
                                <div className="flex items-center space-x-6 lg:space-x-8">
                                    <PaginationPageSize />
                                    <PaginationPageIndex />
                                    <PaginationButtonGroup />
                                </div>
                            </>
                        ) : (
                            <>
                                <PaginationPageSize />
                                <div className="flex items-center space-x-6 lg:space-x-8">
                                    <PaginationPageIndex />
                                    <PaginationButtonGroup />
                                </div>
                            </>
                        )}
                    </>
                )}
            </div>
        </div>
    ) : (
        <DataTableSkeleton rows={10} cols={5} />
    );
}
