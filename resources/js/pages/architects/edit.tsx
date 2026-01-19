import { AddressTable } from '@/components/page/architects/edit/address-table';
import { ArchitectEditForm } from '@/components/page/architects/edit/architect-form';
import { SpecifierTable } from '@/components/page/architects/edit/specifier-table';
import AppLayout from '@/layouts/app-layout';
import { edit } from '@/routes/architects';
import { architect as architectDashboardUrl } from '@/routes/dashboard';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Architect } from '@/types/app/architect';
import { Head, usePage } from '@inertiajs/react';

export default function ArchitectEdit() {
    const { architect } = usePage<SharedData & { architect: Architect }>()
        .props;

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Architect',
            href: architectDashboardUrl().url,
        },
        {
            title: architect.architect_name,
            href: edit(architect.id).url,
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit Architect" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="grid grid-cols-1 gap-4 lg:grid-cols-3">
                    <div className="relative overflow-hidden rounded-xl border p-4">
                        <ArchitectEditForm architect={architect} />
                    </div>
                    <div className="relative overflow-hidden rounded-xl border lg:col-span-2"></div>
                </div>

                <div className="grid grid-cols-1 gap-4 xl:grid-cols-2">
                    <AddressTable architect={architect} />
                    <SpecifierTable architect={architect} />
                </div>
            </div>
        </AppLayout>
    );
}
