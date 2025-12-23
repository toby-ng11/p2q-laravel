import FormLayout from '@/components/form-layout';
import InputError from '@/components/input-error';
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
import { useDialog } from '../dialog-context';

interface ArchitectType {
    id: string;
    architect_type_desc: string;
}

interface ArchitectRep {
    id: string;
    name: string;
}

interface ArchitectFormProps {
    formId: string;
    onProcessingChange?: (processing: boolean) => void;
}

export default function ArchitectForm({
    formId,
    onProcessingChange,
}: ArchitectFormProps) {
    const { user } = usePage<SharedData>().props.auth;
    const { closeDialog } = useDialog('architectDialog');
    const isManagerOrAbove =
        usePage<SharedData>().props.auth.userProperties.isManagerOrAbove;
    const architectTypes = useTanStackQuery<ArchitectType>('/architect-type', [
        'architect-types',
    ]);
    const architectReps = useTanStackQuery<ArchitectRep>('/architect-reps', [
        'architect-reps',
    ]);
    const architectClasses = ['A', 'B', 'C', 'D', 'E'];

    return (
        <Form
            {...store.form()}
            id={formId}
            onStart={() => onProcessingChange?.(true)}
            onFinish={() => onProcessingChange?.(false)}
            onSuccess={closeDialog}
            resetOnSuccess
        >
            {({ errors, validate }) => (
                <FormLayout>
                    <div className="grid gap-2">
                        <Label htmlFor="architect_name">Name</Label>
                        <Input
                            id="architect_name"
                            type="text"
                            name="architect_name"
                            onBlur={() => validate('architect_name')}
                        ></Input>
                        <InputError message={errors.architect_name} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="architect_type_id">Type</Label>
                        <Select name="architect_type_id">
                            <SelectTrigger id="architect_type_id">
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
                                        key={type.architect_type_desc}
                                        value={type.id}
                                    >
                                        {type.architect_type_desc}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        <InputError message={errors.architect_type_id} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="architect_rep_id">Architect Rep.</Label>
                        {isManagerOrAbove ? (
                            <>
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
                                                value={type.id}
                                            >
                                                {type.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <InputError message={errors.architect_rep_id} />
                            </>
                        ) : (
                            <>
                                <Input
                                    id="architect_rep_id"
                                    type="text"
                                    value={user.name}
                                    disabled
                                />
                                <Input
                                    hidden
                                    name="architect_rep_id"
                                    value={user.id}
                                />
                            </>
                        )}
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="class_id">Class</Label>
                        <Select name="class_id">
                            <SelectTrigger id="class_id">
                                <SelectValue placeholder="Select an architect class..." />
                            </SelectTrigger>
                            <SelectContent>
                                {architectClasses.map((architectClass) => (
                                    <SelectItem
                                        key={architectClass}
                                        value={architectClass}
                                    >
                                        {architectClass}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>
                </FormLayout>
            )}
        </Form>
    );
}
