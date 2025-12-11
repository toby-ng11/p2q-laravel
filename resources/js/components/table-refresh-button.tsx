import { Button } from '@/components/ui/button';
import { Tooltip, TooltipContent, TooltipTrigger } from '@/components/ui/tooltip';
import { RefreshCw } from 'lucide-react';

interface DataTableRefreshButtonProps {
    onRefresh: () => void;
    isFetching?: boolean;
    isReady?: boolean;
    tooltip?: string;
    dataUpdatedAt?: number;
}

export function DataTableRefreshButton({ onRefresh, isFetching = false, isReady = true, tooltip = 'Refresh data', dataUpdatedAt }: DataTableRefreshButtonProps) {
    return (
        <div className="absolute top-4 right-4 z-10">
            <div className="flex flex-row items-center gap-2">
                <div className="text-muted-foreground text-xs" aria-live="polite">
                    {dataUpdatedAt ? `Last updated: ${new Date(dataUpdatedAt).toLocaleTimeString()}` : null}
                </div>
                <Tooltip>
                    <TooltipTrigger asChild>
                        <Button
                            type="button"
                            aria-label="Refresh data"
                            variant="outline"
                            className="size-8"
                            onClick={onRefresh}
                            disabled={!isReady || isFetching}
                        >
                            <RefreshCw className={!isReady || isFetching ? 'animate-spin' : ''} />
                        </Button>
                    </TooltipTrigger>
                    <TooltipContent>
                        <p>{tooltip}</p>
                    </TooltipContent>
                </Tooltip>
            </div>
        </div>
    );
}
