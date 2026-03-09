import { totalArchitects } from '@/actions/App/Http/Controllers/DashboardController';
import { useTanStackQuery } from '@/hooks/use-tanstack-query';
import architectReps from '@/routes/architect-reps';
import architectType from '@/routes/architect-type';
import {
    ArchitectGrowth,
    ArchitectRep,
    ArchitectType,
} from '@/types/app/architect';

const useArchitectClasses = () => ['A', 'B', 'C', 'D', 'E'];

const useArchitectTypes = () => {
    return useTanStackQuery<ArchitectType[]>(architectType.index().url, [
        'architect-types',
    ]);
};

const useArchitectReps = () => {
    return useTanStackQuery<ArchitectRep[]>(architectReps.index().url, [
        'architect-reps',
    ]);
};

const useTotalArchitectGrowth = () => {
    return useTanStackQuery<ArchitectGrowth>(totalArchitects.get().url, [
        'total-architect-growth',
    ]);
};

export {
    useArchitectClasses,
    useArchitectReps,
    useArchitectTypes,
    useTotalArchitectGrowth,
};
