import { cn } from '@/lib/utils';
import { cva, VariantProps } from 'class-variance-authority';
import { ReactNode } from 'react';

const formLayoutVariants = cva('grid grid-cols-1 gap-4', {
    variants: {
        size: {
            default: 'lg:grid-cols-2 lg:gap-6',
            sm: 'lg:grid-cols-1',
            lg: 'lg:grid-cols-2 lg:gap-6 xl:grid-cols-3',
        },
    },
    defaultVariants: {
        size: 'default',
    },
});

export default function FormLayout({
    className,
    size,
    children,
}: React.ComponentProps<'div'> &
    VariantProps<typeof formLayoutVariants> & { children: ReactNode }) {
    return (
        <div className="flex max-h-125 flex-col gap-6 overflow-y-auto p-4">
            <div className={cn(formLayoutVariants({ size, className }))}>
                {children}
            </div>
        </div>
    );
}
