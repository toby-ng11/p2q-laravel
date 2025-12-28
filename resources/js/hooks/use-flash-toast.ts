import { FlashData } from '@/types';
import { router } from '@inertiajs/react';
import { useEffect } from 'react';
import { toast } from 'sonner';

export function useFlashToasts() {
    useEffect(() => {
        const toastTypes: Array<keyof FlashData> = [
            'success',
            'error',
            'info',
            'warning',
        ];

        // If not using Inertia::flash(), just check if "flash" from Page exists.
        return router.on(
            'flash',
            (event: CustomEvent<{ flash: FlashData }>) => {
                const flash = event.detail.flash as FlashData;

                if (!flash) return;

                toastTypes.forEach((type) => {
                    const message = flash[type];
                    if (message) {
                        toast[type](message);
                    }
                });
            },
        );
    }, []);
}
