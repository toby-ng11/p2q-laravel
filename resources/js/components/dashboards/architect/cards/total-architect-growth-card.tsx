import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardAction,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { useTotalArchitectGrowth } from '@/hooks/queries/useArchitectQueries';
import { IconTrendingDown, IconTrendingUp } from '@tabler/icons-react';

export function TotalArchitectGrowthCard() {
    const {
        total_architect,
        new_architect_this_month,
        growth_percentage,
        statement,
    } = useTotalArchitectGrowth().data ?? {};

    const isPositive = (growth_percentage ?? 0) >= 0;
    const TrendIcon = isPositive ? IconTrendingUp : IconTrendingDown;

    return (
        <Card className="@container/card">
            <CardHeader>
                <CardDescription>Total Architect</CardDescription>
                <CardTitle className="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                    {total_architect?.toLocaleString()}
                </CardTitle>
                {growth_percentage != null && (
                    <CardAction>
                        <Badge variant="outline">
                            <TrendIcon />
                            {growth_percentage}%
                        </Badge>
                    </CardAction>
                )}
            </CardHeader>
            <CardFooter className="flex-col items-start gap-1.5 text-sm">
                <div className="line-clamp-1 flex gap-2 font-medium">
                    {statement}
                    {growth_percentage != null && (
                        <TrendIcon className="size-4" />
                    )}
                </div>
                {new_architect_this_month && (
                    <div className="text-muted-foreground">
                        {new_architect_this_month} new architect this month
                    </div>
                )}
            </CardFooter>
        </Card>
    );
}
