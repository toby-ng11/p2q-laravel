import { AppContent } from '@/components/app-content';
import { AppShell } from '@/components/app-shell';
import { AppSidebar } from '@/components/app-sidebar';
import { AppSidebarHeader } from '@/components/app-sidebar-header';
import { GlobalDialogProvider } from '@/components/dialog-context';
import { DialogHost } from '@/components/dialogs/dialogs-host';
import { Toaster } from '@/components/ui/sonner';
import { useTheme } from '@/hooks/use-appearance';
import { useErrorsToast } from '@/hooks/use-errors-toast';
import { useFlashToasts } from '@/hooks/use-flash-toast';
import { type BreadcrumbItem } from '@/types';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { type PropsWithChildren } from 'react';

const queryClient = new QueryClient();

export default function AppSidebarLayout({
    children,
    breadcrumbs = [],
}: PropsWithChildren<{ breadcrumbs?: BreadcrumbItem[] }>) {
    const { appearance } = useTheme();
    useFlashToasts();
    useErrorsToast();

    return (
        <QueryClientProvider client={queryClient}>
            <GlobalDialogProvider>
                <AppShell variant="sidebar">
                    <AppSidebar />
                    <AppContent variant="sidebar" className="overflow-x-hidden">
                        <AppSidebarHeader breadcrumbs={breadcrumbs} />
                        {children}
                    </AppContent>
                </AppShell>

                <DialogHost />
                <Toaster richColors position="top-right" theme={appearance} />
            </GlobalDialogProvider>
        </QueryClientProvider>
    );
}
