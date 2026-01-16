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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { store } from '@/routes/architects/addresses';
import { Form } from '@inertiajs/react';
import { useQueryClient } from '@tanstack/react-query';
import { Plus } from 'lucide-react';
import { useMemo, useState } from 'react';
import countryList from 'react-select-country-list';

export function AddAddressButton({
    architectId,
    qKey,
}: {
    architectId: number;
    qKey: (string | number)[];
}) {
    const queryClient = useQueryClient();
    const countries = useMemo(() => countryList().getData(), []);
    const [open, setOpen] = useState(false);
    const handleSuccess = () => {
        queryClient.invalidateQueries({ queryKey: qKey });
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
                                    <DialogTitle>Add Address</DialogTitle>
                                    <DialogDescription>
                                        Fill in the address information, then
                                        click Save.
                                    </DialogDescription>
                                </DialogHeader>
                                <FormLayout>
                                    <div className="grid gap-2">
                                        <Label htmlFor="phys_address1">
                                            Address 1
                                        </Label>
                                        <Input
                                            id="phys_address1"
                                            type="text"
                                            name="phys_address1"
                                            required
                                        ></Input>
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="phys_address2">
                                            Address 2
                                        </Label>
                                        <Input
                                            id="phys_address2"
                                            type="text"
                                            name="phys_address2"
                                        ></Input>
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="phys_city">City</Label>
                                        <Input
                                            id="phys_city"
                                            type="text"
                                            name="phys_city"
                                        ></Input>
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="phys_state">
                                            State
                                        </Label>
                                        <Input
                                            id="phys_state"
                                            type="text"
                                            name="phys_state"
                                        ></Input>
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="phys_postal_code">
                                            Postal Code
                                        </Label>
                                        <Input
                                            id="phys_postal_code"
                                            type="text"
                                            name="phys_postal_code"
                                        ></Input>
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="phys_country">
                                            Country
                                        </Label>
                                        <Select name="phys_country">
                                            <SelectTrigger id="phys_country">
                                                <SelectValue placeholder="Select an country..." />
                                            </SelectTrigger>
                                            <SelectContent>
                                                {countries.map((country) => (
                                                    <SelectItem
                                                        key={country.value}
                                                        value={country.value}
                                                    >
                                                        {country.label}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
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
                                    <div className="grid gap-2">
                                        <Label htmlFor="phone_number">
                                            Phone Number
                                        </Label>
                                        <Input
                                            id="phone_number"
                                            type="text"
                                            name="central_phone_number"
                                        ></Input>
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="address_name">
                                            Address Nickname
                                        </Label>
                                        <Input
                                            id="address_name"
                                            type="text"
                                            name="name"
                                            placeholder="Optional. Can be Work, Office, .... Will be set the same as Address 1 if empty."
                                        ></Input>
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="url">URL</Label>
                                        <Input
                                            id="url"
                                            type="text"
                                            name="url"
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
