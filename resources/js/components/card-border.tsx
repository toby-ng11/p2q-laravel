import { ReactNode } from 'react';

export function CardBorder({ children }: { children: ReactNode }) {
    return (
        <div className="relative h-full flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border">
            {children}
        </div>
    );
}
