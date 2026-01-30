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
import { update } from '@/routes/architects/specifiers';
import { Form } from '@inertiajs/react';
import { useQueryClient } from '@tanstack/react-query';
import { useState } from 'react';
import { SpecifierDeleteButton } from './specifier-delete-button';

interface SpecifierEditButtonProps {
    data: Specifier;
    architectId: number;
    endpoint: string;
    qKey: (string | number)[];
}

export function SpecifierEditButton({
    data,
    architectId,
    endpoint,
    qKey,
}: SpecifierEditButtonProps) {
    const queryClient = useQueryClient();
    const [open, setOpen] = useState(false);

    const handleSuccess = () => {
        queryClient.invalidateQueries({ queryKey: [endpoint, ...qKey] });
        setOpen(false);
    };

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
                <Button
                    variant="ghost"
                    className="text-blue-500 dark:text-blue-300"
                >
                    {data.id}
                </Button>
            </DialogTrigger>
            <DialogContent className="md:min-w-2xl">
                <Form
                    {...update.form({
                        architect: architectId,
                        specifier: data.id,
                    })}
                    options={{ preserveScroll: true }}
                    onSuccess={handleSuccess}
                    resetOnSuccess
                >
                    {({ processing }) => (
                        <>
                            <DialogHeader>
                                <DialogTitle>Edit Specifier</DialogTitle>
                                <DialogDescription>
                                    Edit the address information, then click
                                    Save.
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
                                        defaultValue={data.first_name}
                                        required
                                    ></Input>
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="last_name">Last Name</Label>
                                    <Input
                                        id="last_name"
                                        type="text"
                                        name="last_name"
                                        defaultValue={data.last_name}
                                    ></Input>
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="job_title">Job Title</Label>
                                    <Input
                                        id="job_title"
                                        type="text"
                                        name="job_title"
                                        defaultValue={data.job_title}
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
                                        defaultValue={
                                            data.contact?.central_phone_number
                                        }
                                    ></Input>
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="email_address">Email</Label>
                                    <Input
                                        id="email_address"
                                        type="text"
                                        name="email_address"
                                        defaultValue={
                                            data.contact?.email_address
                                        }
                                    ></Input>
                                </div>
                            </FormLayout>
                            <DialogFooter className="justify-between">
                                <DialogClose asChild>
                                    <Button type="button" variant="outline">
                                        Cancel
                                    </Button>
                                </DialogClose>
                                <SpecifierDeleteButton
                                    architectId={architectId}
                                    specifierId={data.id}
                                    endpoint={endpoint}
                                    qKey={qKey}
                                />
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
    );
}
