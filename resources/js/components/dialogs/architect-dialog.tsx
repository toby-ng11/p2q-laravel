import { useDialog } from '@/components/dialog-context';
import ArchitectForm from '@/components/forms/architect-form';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

export default function ArchitectDialog() {
    const { open, closeDialog } = useDialog('architectDialog');
    const formId = 'create-architect-form';

    return (
        <Dialog
            open={open}
            onOpenChange={(open) => (open ? null : closeDialog())}
        >
            <DialogContent className="md:min-w-2xl lg:min-w-5xl">
                <DialogHeader>
                    <DialogTitle>Create Architect</DialogTitle>
                    <DialogDescription>
                        Enter information for the new architect, then click
                        Create.
                    </DialogDescription>
                </DialogHeader>

                <ArchitectForm formId={formId} />

                <DialogFooter>
                    <DialogClose asChild>
                        <Button type="button" variant="outline">
                            Cancel
                        </Button>
                    </DialogClose>
                    <Button type="submit" form={formId}>
                        Save
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
