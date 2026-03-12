import { ArchitectStats } from '@/components/dashboards/architect/architect-stats';
import ArchitectTable from '@/components/dashboards/architect/architect-table';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/app-layout';
import architects from '@/routes/architects';
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
    const isManagerOrAbove = userProperties.isManagerOrAbove;
    const options = {
        query: {
            user_id: userId,
        },
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Architect Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                {isManagerOrAbove ? (
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
                            <div className="flex flex-col gap-4">
                                <ArchitectStats userId={userId} />
                                <ArchitectTable
                                    endpoint={architects.index(options).url}
                                    tableInfo={{
                                        title: 'Own Architects',
                                        description:
                                            'Here is the list of your architects.',
                                    }}
                                />
                            </div>
                        </TabsContent>
                        <TabsContent value="all-architect">
                            <div className="flex flex-col gap-4">
                                <ArchitectStats />
                                <ArchitectTable
                                    endpoint={architects.index().url}
                                    tableInfo={{
                                        title: 'All Architects',
                                        description:
                                            'Here is the list of all architects.',
                                    }}
                                />
                            </div>
                        </TabsContent>
                    </Tabs>
                ) : (
                    <ArchitectTable
                        endpoint={architects.index(options).url}
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
