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
import { Loader2 } from 'lucide-react';
import { useState } from 'react';

export default function ArchitectDialog() {
    const { open, closeDialog } = useDialog('architectDialog');
    const [isProcessing, setIsProcessing] = useState(false);
    const formId = 'create-architect-form';

    return (
        <Dialog
            open={open}
            onOpenChange={(open) => (open ? null : closeDialog())}
        >
            <DialogContent className="md:min-w-2xl">
                <DialogHeader>
                    <DialogTitle>Create Architect</DialogTitle>
                    <DialogDescription>
                        Enter information for the new architect, then click
                        Create. You will be able to fill in addresses and
                        specifiers in the next screen.
                    </DialogDescription>
                </DialogHeader>

                <ArchitectForm formId={formId} onProcessingChange={setIsProcessing} />

                <DialogFooter>
                    <DialogClose asChild>
                        <Button type="button" variant="outline">
                            Cancel
                        </Button>
                    </DialogClose>
                    <Button type="submit" form={formId} disabled={isProcessing}>
                        Save
                        {isProcessing && (
                            <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                        )}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
