import { Skeleton } from '@/components/ui/skeleton';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

interface DataTableSkeletonProps {
    rows?: number;
    cols?: number;
}

export function DataTableSkeleton({ rows = 5, cols = 5 }: DataTableSkeletonProps) {
    const rowNumber = Array.from({ length: rows });
    const columnNumber = Array.from({ length: cols });

    return (
        <Table>
            <TableHeader className="bg-muted sticky top-0 z-10 [&_tr]:border-b">
                <TableRow>
                    {columnNumber.map((_, i) => (
                        <TableHead key={i}>
                            <Skeleton className="h-9 w-20" />
                        </TableHead>
                    ))}
                </TableRow>
            </TableHeader>
            <TableBody>
                {rowNumber.map((_, i) => (
                    <TableRow key={i}>
                        {columnNumber.map((_, j) => (
                            <TableCell key={j}>
                                <Skeleton className="h-5 w-full" />
                            </TableCell>
                        ))}
                    </TableRow>
                ))}
            </TableBody>
        </Table>
    );
}
