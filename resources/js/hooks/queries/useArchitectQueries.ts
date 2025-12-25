import { useTanStackQuery } from '@/hooks/use-tanstack-query';
import architectReps from '@/routes/architect-reps';
import architectType from '@/routes/architect-type';
import { ArchitectRep, ArchitectType } from '@/types/architect';

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

export { useArchitectClasses, useArchitectReps, useArchitectTypes };
