import FormLayout from '@/components/form-layout';
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
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { store } from '@/routes/architects/specifiers';
import { Form } from '@inertiajs/react';
import { useQueryClient } from '@tanstack/react-query';
import { Plus } from 'lucide-react';
import { useState } from 'react';

interface AddSpecifierButtonProps {
    architectId: number;
    endpoint: string;
    qKey: (string | number)[];
}

export function AddSpecifierButton({
    architectId,
    endpoint,
    qKey,
}: AddSpecifierButtonProps) {
    const queryClient = useQueryClient();
    const [open, setOpen] = useState(false);
    const handleSuccess = () => {
        queryClient.invalidateQueries({ queryKey: [endpoint, ...qKey] });
        setOpen(false);
    };
    return (
        <div className="absolute top-4 right-4 z-10">
            <Dialog open={open} onOpenChange={setOpen}>
                <DialogTrigger asChild>
                    <Button variant="outline" size="icon-sm">
                        <Plus />
                    </Button>
                </DialogTrigger>
                <DialogContent className="md:min-w-2xl">
                    <Form
                        {...store.form({
                            architect: architectId,
                        })}
                        options={{ preserveScroll: true }}
                        onSuccess={handleSuccess}
                        resetOnSuccess
                    >
                        {({ processing }) => (
                            <>
                                <DialogHeader>
                                    <DialogTitle>Add Specifier</DialogTitle>
                                    <DialogDescription>
                                        Fill in the specifier information, then
                                        click Save.
                                    </DialogDescription>
                                </DialogHeader>
                                <FormLayout>
                                    <div className="grid gap-2">
                                        <Label htmlFor="first_name">
                                            First Name
                                        </Label>
                                        <Input
                                            id="first_name"
                                            type="text"
                                            name="first_name"
                                            required
                                        ></Input>
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="last_name">
                                            Last Name
                                        </Label>
                                        <Input
                                            id="last_name"
                                            type="text"
                                            name="last_name"
                                        ></Input>
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="job_title">
                                            Job Title
                                        </Label>
                                        <Input
                                            id="job_title"
                                            type="text"
                                            name="job_title"
                                        ></Input>
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="central_phone_number">
                                            Phone Number
                                        </Label>
                                        <Input
                                            id="central_phone_number"
                                            type="text"
                                            name="central_phone_number"
                                        ></Input>
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="email_address">
                                            Email
                                        </Label>
                                        <Input
                                            id="email_address"
                                            type="text"
                                            name="email_address"
                                        ></Input>
                                    </div>
                                </FormLayout>
                                <DialogFooter>
                                    <DialogClose asChild>
                                        <Button type="button" variant="outline">
                                            Cancel
                                        </Button>
                                    </DialogClose>
                                    <Button type="submit" disabled={processing}>
                                        Save
                                        {processing && <Spinner />}
                                    </Button>
                                </DialogFooter>
                            </>
                        )}
                    </Form>
                </DialogContent>
            </Dialog>
        </div>
    );
}
