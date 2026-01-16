import InputError from '@/components/input-error';
import { InputLoading } from '@/components/input-loading';
import { Button } from '@/components/ui/button';
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
import { Spinner } from '@/components/ui/spinner';
import {
    useArchitectClasses,
    useArchitectReps,
    useArchitectTypes,
} from '@/hooks/queries/useArchitectQueries';
import { useTanStackQuery } from '@/hooks/use-tanstack-query';
import architectReps from '@/routes/architect-reps';
import { update } from '@/routes/architects';
import { SharedData } from '@/types';
import { Architect, ArchitectRep } from '@/types/app/architect';
import { Form, usePage } from '@inertiajs/react';

export function ArchitectEditForm({ architect }: { architect: Architect }) {
    const isManagerOrAbove =
        usePage<SharedData>().props.auth.userProperties.isManagerOrAbove;
    const architectTypeData = useArchitectTypes();
    const architectRepData = useArchitectReps();
    const architectClasses = useArchitectClasses();
    const ownArchitectRepData = useTanStackQuery<ArchitectRep>(
        architectReps.show(architect.architect_rep_id).url,
        ['architect-reps', architect.architect_rep_id],
    );

    const isLoadingForManager =
        architectTypeData.isLoading || architectRepData.isLoading;

    const isLoadingForArchitectRep =
        architectTypeData.isLoading || ownArchitectRepData.isLoading;

    return (
        <Form {...update.form(architect.id)} options={{ preserveScroll: true }}>
            {({ processing, errors }) => (
                <FieldGroup>
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
                                <InputError message={errors.architect_name} />
                            </Field>

                            <Field>
                                <FieldLabel htmlFor="architect_type_id">
                                    Type
                                </FieldLabel>
                                {architectTypeData.isLoading ? (
                                    <InputLoading />
                                ) : (
                                    <>
                                        <Select
                                            name="architect_type_id"
                                            defaultValue={architect.architect_type_id.toString()}
                                        >
                                            <SelectTrigger id="architect_type_id">
                                                <SelectValue placeholder="Select an architect type..." />
                                            </SelectTrigger>
                                            <SelectContent>
                                                {architectTypeData.data?.map(
                                                    (type) => (
                                                        <SelectItem
                                                            key={
                                                                type.architect_type_desc
                                                            }
                                                            value={type.id.toString()}
                                                        >
                                                            {
                                                                type.architect_type_desc
                                                            }
                                                        </SelectItem>
                                                    ),
                                                )}
                                            </SelectContent>
                                        </Select>
                                        <InputError
                                            message={errors.architect_type_id}
                                        />
                                    </>
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
                                            <>
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
                                                                    key={
                                                                        type.id
                                                                    }
                                                                    value={type.id.toString()}
                                                                >
                                                                    {type.name}
                                                                </SelectItem>
                                                            ),
                                                        )}
                                                    </SelectContent>
                                                </Select>
                                                <InputError
                                                    message={
                                                        errors.architect_rep_id
                                                    }
                                                />
                                            </>
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
                                                value={
                                                    ownArchitectRepData.data
                                                        ?.name
                                                }
                                                disabled
                                            />
                                        )}
                                    </>
                                )}
                            </Field>

                            <Field>
                                <FieldLabel htmlFor="class_id">
                                    Class
                                </FieldLabel>
                                <Select
                                    name="class_id"
                                    defaultValue={architect.class_id}
                                >
                                    <SelectTrigger id="class_id">
                                        <SelectValue placeholder="Select an architect class..." />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {architectClasses.map(
                                            (architectClass) => (
                                                <SelectItem
                                                    key={architectClass}
                                                    value={architectClass}
                                                >
                                                    {architectClass}
                                                </SelectItem>
                                            ),
                                        )}
                                    </SelectContent>
                                </Select>
                                <InputError message={errors.class_id} />
                            </Field>
                        </FieldGroup>
                    </FieldSet>
                    <Field
                        orientation="horizontal"
                        className="flex justify-end"
                    >
                        <Button
                            type="submit"
                            disabled={
                                isManagerOrAbove
                                    ? processing || isLoadingForManager
                                    : processing || isLoadingForArchitectRep
                            }
                        >
                            Submit
                            {processing && <Spinner />}
                        </Button>
                    </Field>
                </FieldGroup>
            )}
        </Form>
    );
}
