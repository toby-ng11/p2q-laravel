import { TotalArchitectGrowthCard } from './cards/total-architect-growth-card';

interface ArchitectStatsProps {
    userId?: number;
}

export function ArchitectStats({ userId }: ArchitectStatsProps) {
    return (
        <div className="grid auto-rows-min gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <TotalArchitectGrowthCard userId={userId} />
            <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"></div>
            <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"></div>
            <div className="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"></div>
        </div>
    );
}
