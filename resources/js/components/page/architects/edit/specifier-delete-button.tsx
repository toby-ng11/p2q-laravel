import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Spinner } from '@/components/ui/spinner';
import { destroy } from '@/routes/architects/specifiers';
import { Form } from '@inertiajs/react';
import { useQueryClient } from '@tanstack/react-query';
import { Trash2 } from 'lucide-react';
import { useState } from 'react';

interface SpecifierDeleteButtonProps {
    architectId: number;
    specifierId: number;
    endpoint: string;
    qKey: (string | number)[];
    isInTable?: boolean;
}

export function SpecifierDeleteButton({
    architectId,
    specifierId,
    endpoint,
    qKey,
    isInTable = false,
}: SpecifierDeleteButtonProps) {
    const queryClient = useQueryClient();
    const [open, setOpen] = useState(false);

    const handleSuccess = () => {
        queryClient.invalidateQueries({ queryKey: [endpoint, ...qKey] });
        setOpen(false);
    };

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
                {isInTable ? (
                    <Button type="button" variant="ghost" size="icon-sm">
                        <Trash2 />
                    </Button>
                ) : (
                    <Button type="button" variant="destructive">
                        Delete
                    </Button>
                )}
            </DialogTrigger>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Are you sure?</DialogTitle>
                    <DialogDescription>
                        This action cannot be undone. This will permanently
                        delete the specifier.
                    </DialogDescription>
                </DialogHeader>
                <Form
                    {...destroy.form({
                        architect: architectId,
                        specifier: specifierId,
                    })}
                    options={{ preserveScroll: true }}
                    onSuccess={handleSuccess}
                >
                    {({ processing }) => (
                        <DialogFooter>
                            <DialogClose asChild>
                                <Button type="button" variant="outline">
                                    Cancel
                                </Button>
                            </DialogClose>
                            <Button
                                className="bg-destructive text-white hover:bg-destructive/90 dark:bg-destructive/50 dark:hover:bg-destructive/60"
                                disabled={processing}
                                type="submit"
                            >
                                Delete
                                {processing && <Spinner />}
                            </Button>
                        </DialogFooter>
                    )}
                </Form>
            </DialogContent>
        </Dialog>
    );
}
