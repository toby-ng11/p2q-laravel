import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
} from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';

export function InputLoading() {
    return (
        <InputGroup data-disabled>
            <InputGroupInput placeholder="Loading..." disabled />
            <InputGroupAddon align="inline-end">
                <Spinner />
            </InputGroupAddon>
        </InputGroup>
    );
}
