import { DataTable } from '@/components/data-table';
import { DataTableColumnHeader } from '@/components/table-header';
import { useTanStackQuery } from '@/hooks/use-tanstack-query';
import { index } from '@/routes/architects/addresses';
import { Architect } from '@/types/app/architect';
import { ColumnDef } from '@tanstack/react-table';
import { useMemo } from 'react';
import { AddAddressButton } from './add-address-button';
import { AddressDeleteDialog } from './address-delete-dialog';
import { AddressDialog } from './address-dialog';

export function AddressTable({ architect }: { architect: Architect }) {
    const qKey = useMemo(
        () => ['architect-address', architect.id],
        [architect.id],
    );
    const endpoint = index(architect.id).url;
    const { data: addressData = [], isFetching } = useTanStackQuery<Address[]>(
        endpoint,
        qKey,
    );

    const columns = useMemo<ColumnDef<Address>[]>(
        () => [
            {
                accessorKey: 'id',
                header: ({ column }) => (
                    <DataTableColumnHeader column={column} title="ID" />
                ),
                cell: ({ row }) => {
                    const data = row.original;
                    return (
                        <AddressDialog
                            architectId={architect.id}
                            qKey={[endpoint, ...qKey]}
                            data={data}
                        />
                    );
                },
                enableHiding: false,
                meta: 'ID',
            },
            {
                accessorKey: 'name',
                header: ({ column }) => (
                    <DataTableColumnHeader column={column} title="Name" />
                ),
                cell: ({ row }) => (
                    <div className="max-w-75 truncate font-medium">
                        {row.getValue('name')}
                    </div>
                ),
                enableHiding: false,
                meta: 'Name',
            },
            {
                id: 'addressDetails',
                accessorKey: 'phys_address1',
                header: ({ column }) => (
                    <DataTableColumnHeader column={column} title="Details" />
                ),
                cell: ({ row }) => {
                    const data = row.original;
                    const lines = [
                        {
                            id: 'addr',
                            value: [data.phys_address1, data.phys_address2]
                                .filter(Boolean)
                                .join(', '),
                        },
                        {
                            id: 'city',
                            value: [
                                [data.phys_city, data.phys_state]
                                    .filter(Boolean)
                                    .join(', '),
                                data.phys_postal_code,
                            ]
                                .filter(Boolean)
                                .join(' '),
                        },
                        { id: 'country', value: data.phys_country },
                        { id: 'phone', value: data.central_phone_number },
                        { id: 'email', value: data.email_address },
                        { id: 'url', value: data.url, isLink: true },
                    ].filter((line) => line.value);

                    return (
                        <>
                            {lines.map((line) => (
                                <p key={line.id}>
                                    {line.isLink ? (
                                        <a
                                            href={
                                                line.value.startsWith('http')
                                                    ? line.value
                                                    : `https://${line.value}`
                                            }
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="text-blue-500 hover:underline"
                                        >
                                            {line.value}
                                        </a>
                                    ) : (
                                        line.value
                                    )}
                                </p>
                            ))}
                        </>
                    );
                },

                enableHiding: false,
                meta: 'Details',
            },
            {
                accessorKey: 'delete',
                header: () => null,
                cell: ({ row }) => {
                    const addressId = row.original.id;
                    return (
                        <AddressDeleteDialog
                            architectId={architect.id}
                            addressId={addressId}
                            qKey={[endpoint, ...qKey]}
                            isInTable={true}
                        />
                    );
                },
            },
        ],
        [architect.id, qKey, endpoint],
    );

    const tableSkeletonProps = {
        rows: 3,
        columns: 3,
    };

    return (
        <div className="relative h-full flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border">
            <AddAddressButton
                architectId={architect.id}
                qKey={[endpoint, ...qKey]}
            />
            <div className="flex flex-1 flex-col gap-4 p-2">
                <div className="flex flex-col gap-1">
                    <h2 className="font-medium">Addresses</h2>
                    <p className="text-sm text-muted-foreground">
                        All addresses for this architect.
                    </p>
                </div>

                <DataTable
                    columns={columns}
                    data={addressData}
                    sortingColumns={[{ id: 'id', desc: true }]}
                    isFetching={isFetching}
                    tableSkeleton={tableSkeletonProps}
                />
            </div>
        </div>
    );
}
