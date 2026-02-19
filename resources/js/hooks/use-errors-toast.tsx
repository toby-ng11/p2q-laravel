import { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { ErrorBag, Errors } from 'node_modules/@inertiajs/core/types/types';
import { useEffect, useRef } from 'react';
import { toast } from 'sonner';

export function useErrorsToast() {
    const { errors } = usePage<SharedData>().props;
    const previousErrorsRef = useRef<string>('');
    const flattenErrors = (errorsObj: Errors & ErrorBag): Record<string, string> => {
        const flattened: Record<string, string> = {};

        Object.entries(errorsObj).forEach(([key, value]) => {
            if (typeof value === 'string') {
                flattened[key] = value;
            } else if (typeof value === 'object' && value !== null) {
                Object.entries(value).forEach(([nestedKey, nestedValue]) => {
                    flattened[nestedKey] = nestedValue as string;
                });
            }
        });

        return flattened;
    };

    useEffect(() => {
        const flatErrors = flattenErrors(errors);
        const errorsArray = Object.entries(flatErrors);
        const errorsString = JSON.stringify(flatErrors);

        if (
            errorsArray.length > 0 &&
            errorsString !== previousErrorsRef.current
        ) {
            toast.error(
                <div>
                    <p>Something went wrong</p>
                    {errorsArray.map(([errorKey, errorValue]) => (
                        <p key={errorKey}>
                            <b>{errorKey}</b>: {errorValue}
                        </p>
                    ))}
                </div>,
            );
            previousErrorsRef.current = errorsString;
        }

        if (errorsArray.length === 0) {
            previousErrorsRef.current = '';
        }
    }, [errors]);
}
