import { Suspense, lazy } from 'react';

const ArchitectDialog = lazy(
    () => import('@/components/dialogs/architect-dialog'),
);

export function DialogHost() {
    return (
        <Suspense fallback={null}>
            <ArchitectDialog />
        </Suspense>
    );
}
