import { DataTable } from '@/components/data-table';
import { DataTableColumnHeader } from '@/components/table-header';
import { DataTableRefreshButton } from '@/components/table-refresh-button';
import { Checkbox } from '@/components/ui/checkbox';
import { useTanStackQuery } from '@/hooks/use-tanstack-query';
import architects from '@/routes/architects';
import userPreference from '@/routes/user-preference';
import { Link } from '@inertiajs/react';
import { ColumnDef } from '@tanstack/react-table';
import { useMemo } from 'react';
import { ArchitectDeleteButton } from './architect-delete-button';

interface ArchitectTable {
    id: number;
    architect_name: string;
    architect_rep: {
        name: string;
    };
    architect_type: {
        architect_type_desc: string;
    };
    class_id: string;
    created_at: string;
}

interface ArchitectTableProps {
    endpoint: string;
    tableInfo: {
        title: string;
        description: string;
    };
}
export default function ArchitectTable({
    endpoint,
    tableInfo,
}: ArchitectTableProps) {
    const prefKey = 'architect-dashboard-architect-table-column-visibility';

    const getUrl = userPreference.show.url(prefKey);
    const postUrl = userPreference.update.url(prefKey);
    const qKey = useMemo(() => ['architect-dashboard', 'architect-table'], []);

    const {
        data: architectData = [],
        isFetching,
        refetch,
        dataUpdatedAt,
    } = useTanStackQuery<ArchitectTable[]>(endpoint, qKey);

    const columns = useMemo<ColumnDef<ArchitectTable>[]>(
        () => [
            {
                id: 'select',
                header: ({ table }) => (
                    <div className="flex items-center justify-center">
                        <Checkbox
                            checked={
                                table.getIsAllPageRowsSelected() ||
                                (table.getIsSomePageRowsSelected() &&
                                    'indeterminate')
                            }
                            onCheckedChange={(value) =>
                                table.toggleAllPageRowsSelected(!!value)
                            }
                            aria-label="Select all"
                        />
                    </div>
                ),
                cell: ({ row }) => (
                    <div className="flex items-center justify-center">
                        <Checkbox
                            checked={row.getIsSelected()}
                            onCheckedChange={(value) =>
                                row.toggleSelected(!!value)
                            }
                            aria-label="Select row"
                        />
                    </div>
                ),
                enableSorting: false,
                enableHiding: false,
            },
            {
                accessorKey: 'id',
                header: ({ column }) => (
                    <DataTableColumnHeader column={column} title="ID" />
                ),
                cell: ({ row }) => {
                    const architectId = row.getValue<number>('id');
                    return (
                        <Link
                            href={architects.edit(architectId)}
                            className="text-blue-500 dark:text-blue-300"
                        >
                            {architectId}
                        </Link>
                    );
                },
                enableHiding: false,
                meta: 'ID',
            },
            {
                accessorKey: 'architect_name',
                header: ({ column }) => (
                    <DataTableColumnHeader column={column} title="Architect" />
                ),
                cell: ({ row }) => (
                    <div className="max-w-75 truncate font-medium">
                        {row.getValue('architect_name')}
                    </div>
                ),
                meta: 'Architect',
            },
            {
                id: 'architectType',
                accessorKey: 'architect_type.architect_type_desc',
                header: ({ column }) => (
                    <DataTableColumnHeader column={column} title="Type" />
                ),
                meta: 'Type',
                filterFn: 'arrIncludesSome',
            },
            {
                id: 'architectRepName',
                accessorKey: 'architect_rep.name',
                header: ({ column }) => (
                    <DataTableColumnHeader
                        column={column}
                        title="Architect Rep."
                    />
                ),
                meta: 'Architect Rep.',
                filterFn: 'arrIncludesSome',
            },
            {
                id: 'created_at',
                accessorFn: (row) => row.created_at,
                header: ({ column }) => (
                    <DataTableColumnHeader column={column} title="Created At" />
                ),
                cell: ({ row }) =>
                    new Date(row.getValue('created_at')).toLocaleDateString(
                        'en-CA',
                        { month: 'short', day: 'numeric', year: 'numeric' },
                    ),
                sortingFn: 'datetime',
                meta: 'Created At',
            },
            {
                accessorKey: 'delete',
                header: () => null,
                cell: ({ row }) => {
                    return (
                        <ArchitectDeleteButton
                            architectId={row.original.id}
                            qKey={[endpoint, ...qKey]}
                            isInTable={true}
                        />
                    );
                },
                enableHiding: false,
            },
        ],
        [endpoint, qKey],
    );

    return (
        <div className="relative h-full flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border">
            <DataTableRefreshButton
                onRefresh={() => refetch({ cancelRefetch: true })}
                isFetching={isFetching}
                dataUpdatedAt={dataUpdatedAt}
            />

            <div className="flex flex-1 flex-col gap-4 p-2">
                <div className="flex flex-col gap-1">
                    <h2 className="text-2xl font-semibold tracking-tight">
                        {tableInfo.title}
                    </h2>
                    <p className="text-muted-foreground">
                        {tableInfo.description}
                    </p>
                </div>

                <DataTable
                    columns={columns}
                    data={architectData}
                    sortingColumns={[{ id: 'id', desc: true }]}
                    hasPagination
                    hasSelect
                    hasSearch={{
                        searchColumn: 'architect_name',
                        searchPlaceholder: 'Search architects...',
                    }}
                    hasVisibilityControl={{
                        retriveEndpoint: getUrl,
                        saveEndpoint: postUrl,
                    }}
                    hasFacetedFilter={{
                        facetedFilters: [
                            { columnId: 'architectType', title: 'Type' },
                            {
                                columnId: 'architectRepName',
                                title: 'Architect Rep.',
                            },
                        ],
                    }}
                    isFetching={isFetching}
                />
            </div>
        </div>
    );
}
