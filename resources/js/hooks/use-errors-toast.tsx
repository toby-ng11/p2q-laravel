import { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { useEffect, useRef } from 'react';
import { toast } from 'sonner';

export function useErrorsToast() {
    const { errors } = usePage<SharedData>().props;
    const previousErrorsRef = useRef<string>('');

    useEffect(() => {
        const errorsArray = Object.entries(errors);
        const errorsString = JSON.stringify(errors);

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
