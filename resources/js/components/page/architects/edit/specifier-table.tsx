import { DataTable } from '@/components/data-table';
import { FormatEmail } from '@/components/format-email';
import { DataTableColumnHeader } from '@/components/table-header';
import { useTanStackQuery } from '@/hooks/use-tanstack-query';
import { index } from '@/routes/architects/specifiers';
import { ColumnDef } from '@tanstack/react-table';
import { useMemo } from 'react';
import { AddSpecifierButton } from './add-specifier-button';
import { SpecifierDeleteButton } from './specifier-delete-button';
import { SpecifierEditButton } from './specifier-edit-button';

export function SpecifierTable({ architectId }: { architectId: number }) {
    const qKey = useMemo(
        () => ['architect-specifier', architectId],
        [architectId],
    );
    const endpoint = index(architectId).url;
    const { data: specifierData = [], isFetching } = useTanStackQuery<
        Specifier[]
    >(endpoint, qKey);

    const columns = useMemo<ColumnDef<Specifier>[]>(
        () => [
            {
                accessorKey: 'id',
                header: ({ column }) => (
                    <DataTableColumnHeader column={column} title="ID" />
                ),
                cell: ({ row }) => {
                    const data = row.original;
                    return (
                        <SpecifierEditButton
                            data={data}
                            architectId={architectId}
                            endpoint={endpoint}
                            qKey={qKey}
                        />
                    );
                },
                enableHiding: false,
                meta: 'ID',
            },
            {
                accessorKey: 'name_and_title',
                header: ({ column }) => (
                    <DataTableColumnHeader
                        column={column}
                        title="Name & Title"
                    />
                ),
                cell: ({ row }) => {
                    const data = row.original;
                    const lines = [
                        {
                            id: 'full_name',
                            value: [data.first_name, data.last_name]
                                .filter(Boolean)
                                .join(' '),
                            isBold: true,
                        },
                        {
                            id: 'job_title',
                            value: data.job_title,
                        },
                    ].filter((line) => line.value);

                    return lines.map((line) => (
                        <div key={line.id}>
                            {line.isBold ? (
                                <p className="font-medium">{line.value}</p>
                            ) : (
                                line.value
                            )}
                        </div>
                    ));
                },
                enableHiding: false,
            },
            {
                accessorKey: 'contacts',
                header: ({ column }) => (
                    <DataTableColumnHeader column={column} title="Contacts" />
                ),
                cell: ({ row }) => {
                    const data = row.original;
                    const lines = [
                        {
                            id: 'phone',
                            value: data.contact?.central_phone_number,
                        },
                        {
                            id: 'email',
                            value: data.contact?.email_address,
                            isEmail: true,
                        },
                    ].filter((line) => line.value);

                    return lines.map((line) => (
                        <p key={line.id}>
                            {line.isEmail ? (
                                <FormatEmail email={line.value} />
                            ) : (
                                line.value
                            )}
                        </p>
                    ));
                },
                enableHiding: false,
            },
            {
                accessorKey: 'delete',
                header: () => null,
                cell: ({ row }) => {
                    const specifierId = row.original.id;
                    return (
                        <SpecifierDeleteButton
                            architectId={architectId}
                            specifierId={specifierId}
                            endpoint={endpoint}
                            qKey={qKey}
                            isInTable={true}
                        />
                    );
                },
                enableHiding: false,
            },
        ],
        [architectId, endpoint, qKey],
    );

    const tableSkeletonProps = {
        rows: 3,
        columns: 3,
    };

    return (
        <div className="relative h-full flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border">
            <AddSpecifierButton
                architectId={architectId}
                endpoint={endpoint}
                qKey={qKey}
            />

            <div className="flex flex-1 flex-col gap-4 p-2">
                <div className="flex flex-col gap-1">
                    <h2 className="font-medium">Specifiers</h2>
                    <p className="text-sm text-muted-foreground">
                        All specifiers for this architect.
                    </p>
                </div>

                <DataTable
                    columns={columns}
                    data={specifierData}
                    sortingColumns={[{ id: 'id', desc: true }]}
                    isFetching={isFetching}
                    tableSkeleton={tableSkeletonProps}
                />
            </div>
        </div>
    );
}
