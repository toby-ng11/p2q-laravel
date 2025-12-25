import { InputLoading } from '@/components/input-loading';
import {
    Field,
    FieldDescription,
    FieldGroup,
    FieldLabel,
    FieldLegend,
    FieldSet,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    useArchitectClasses,
    useArchitectReps,
    useArchitectTypes,
} from '@/hooks/queries/useArchitectQueries';
import { useTanStackQuery } from '@/hooks/use-tanstack-query';
import architectReps from '@/routes/architect-reps';
import { SharedData } from '@/types';
import { Architect, ArchitectRep } from '@/types/architect';
import { usePage } from '@inertiajs/react';

interface ArchitectEditFormProps {
    architect: Architect;
}

export function ArchitectEditForm({ architect }: ArchitectEditFormProps) {
    const isManagerOrAbove =
        usePage<SharedData>().props.auth.userProperties.isManagerOrAbove;
    const architectTypeData = useArchitectTypes();
    const architectRepData = useArchitectReps();
    const architectClasses = useArchitectClasses();
    const ownArchitectRepData = useTanStackQuery<ArchitectRep>(
        architectReps.show(architect.architect_rep_id).url,
        ['architect-reps', architect.architect_rep_id],
    );
    return (
        <form>
            <FieldSet>
                <FieldLegend>Details</FieldLegend>
                <FieldDescription>
                    Edit the Architect details, then click Save.
                </FieldDescription>
                <FieldGroup>
                    <Field>
                        <FieldLabel htmlFor="architect_name">
                            Architect name
                        </FieldLabel>
                        <Input
                            id="architect_name"
                            name="architect_name"
                            defaultValue={architect.architect_name}
                        />
                    </Field>

                    <Field>
                        <FieldLabel htmlFor="architect_type_id">
                            Type
                        </FieldLabel>
                        {architectTypeData.isLoading ? (
                            <InputLoading />
                        ) : (
                            <Select
                                name="architect_type_id"
                                defaultValue={architect.architect_type_id.toString()}
                            >
                                <SelectTrigger id="architect_type_id">
                                    <SelectValue placeholder="Select an architect type..." />
                                </SelectTrigger>
                                <SelectContent>
                                    {architectTypeData.data?.map((type) => (
                                        <SelectItem
                                            key={type.architect_type_desc}
                                            value={type.id.toString()}
                                        >
                                            {type.architect_type_desc}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        )}
                    </Field>

                    <Field>
                        <FieldLabel htmlFor="architect_rep_id">
                            Architect representative
                        </FieldLabel>
                        {isManagerOrAbove ? (
                            <>
                                {architectRepData.isLoading ? (
                                    <InputLoading />
                                ) : (
                                    <Select
                                        name="architect_rep_id"
                                        defaultValue={architect.architect_rep_id.toString()}
                                    >
                                        <SelectTrigger id="architect_rep_id">
                                            <SelectValue placeholder="Select an architect representative..." />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {architectRepData.data?.map(
                                                (type) => (
                                                    <SelectItem
                                                        key={type.id}
                                                        value={type.id.toString()}
                                                    >
                                                        {type.name}
                                                    </SelectItem>
                                                ),
                                            )}
                                        </SelectContent>
                                    </Select>
                                )}
                            </>
                        ) : (
                            <>
                                {ownArchitectRepData.isLoading ? (
                                    <InputLoading />
                                ) : (
                                    <Input
                                        id="architect_rep_id"
                                        type="text"
                                        value={ownArchitectRepData.data?.name}
                                        disabled
                                    />
                                )}
                            </>
                        )}
                    </Field>

                    <Field>
                        <FieldLabel htmlFor="class_id">Class</FieldLabel>
                        <Select
                            name="class_id"
                            defaultValue={architect.class_id}
                        >
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
                    </Field>
                </FieldGroup>
            </FieldSet>
        </form>
    );
}
