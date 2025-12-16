import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useTanStackQuery } from '@/hooks/use-tanstack-query';
import { store } from '@/routes/architects';
import { SharedData } from '@/types';
import { Form, usePage } from '@inertiajs/react';
import { Loader2 } from 'lucide-react';

interface ArchitectType {
    id: string | number;
    architect_type_desc: string;
}

interface ArchitectRep {
    id: string | number;
    name: string;
}

export default function ArchitectForm({ formId }: { formId: string }) {
    const { user } = usePage<SharedData>().props.auth;
    const isManagerOrAbove =
        usePage<SharedData>().props.auth.userProperties.isManagerOrAbove;
    const architectTypes = useTanStackQuery<ArchitectType>('/architect-type', [
        'architect-types',
    ]);
    const architectReps = useTanStackQuery<ArchitectRep>('/architect-reps', [
        'architect-reps',
    ]);

    return (
        <Form id={formId} {...store.form()}>
            <div className="flex max-h-125 flex-col gap-6 overflow-y-auto p-4">
                <div className="grid grid-cols-1 gap-4 lg:grid-cols-2 xl:grid-cols-3 xl:gap-6">
                    <div className="grid gap-2">
                        <Label htmlFor="architect_name">Name</Label>
                        <Input
                            id="architect_name"
                            type="text"
                            name="architect_name"
                        ></Input>

                        <Label htmlFor="architect_type">Type</Label>
                        <Select name="architect_type">
                            <SelectTrigger id="architect_type">
                                <SelectValue placeholder="Select an architect type..." />
                            </SelectTrigger>
                            <SelectContent>
                                {architectTypes.isLoading && (
                                    <SelectItem key="loading" value="loading">
                                        <Loader2 className="animate-spin" />
                                    </SelectItem>
                                )}
                                {architectTypes.data?.map((type) => (
                                    <SelectItem
                                        key={type.id}
                                        value={type.architect_type_desc}
                                    >
                                        {type.architect_type_desc}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>

                        <Label htmlFor="architect_rep_id">Architect Rep.</Label>
                        {isManagerOrAbove ? (
                            <Select name="architect_rep_id">
                                <SelectTrigger id="architect_rep_id">
                                    <SelectValue placeholder="Select an architect representative..." />
                                </SelectTrigger>
                                <SelectContent>
                                    {architectReps.isLoading && (
                                        <SelectItem
                                            key="loading"
                                            value="loading"
                                        >
                                            <Loader2 className="animate-spin" />
                                        </SelectItem>
                                    )}
                                    {architectReps.data?.map((type) => (
                                        <SelectItem
                                            key={type.id}
                                            value={type.name}
                                        >
                                            {type.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        ) : (
                            <>
                                <Input
                                    id="architect_rep_id"
                                    type="text"
                                    value={user.name}
                                    disabled
                                />
                                <Input hidden name="architect_rep_id" />
                            </>
                        )}
                    </div>
                </div>
            </div>
        </Form>
    );
}
