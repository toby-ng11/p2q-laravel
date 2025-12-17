import ArchitectTable from '@/components/dashboards/architect/architect-table';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/app-layout';
import { architect } from '@/routes/dashboard';
import { SharedData, type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Architect',
        href: architect().url,
    },
];

export default function ArchitectDashboard() {
    const { user, userProperties } = usePage<SharedData>().props.auth;
    const userId = user.id;
    const isAdmin = userProperties.isAdministrator;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Architect Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="grid auto-rows-min gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                    <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                    <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                    <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                </div>

                {isAdmin ? (
                    <Tabs defaultValue="own-architect">
                        <TabsList>
                            <TabsTrigger value="own-architect">
                                Own Architect
                            </TabsTrigger>
                            <TabsTrigger value="all-architect">
                                All Architect
                            </TabsTrigger>
                        </TabsList>

                        <TabsContent value="own-architect">
                            <ArchitectTable
                                endpoint={'/architects?user_id=' + userId}
                                tableInfo={{
                                    title: 'Own Architects',
                                    description:
                                        'Here is the list of your architects.',
                                }}
                            />
                        </TabsContent>
                        <TabsContent value="all-architect">
                            <ArchitectTable
                                endpoint="/architects"
                                tableInfo={{
                                    title: 'All Architects',
                                    description:
                                        'Here is the list of all architects.',
                                }}
                            />
                        </TabsContent>
                    </Tabs>
                ) : (
                    <ArchitectTable
                        endpoint={'/architects?user_id=' + userId}
                        tableInfo={{
                            title: 'Own Architects',
                            description: 'Here is the list of your architects.',
                        }}
                    />
                )}
            </div>
        </AppLayout>
    );
}
