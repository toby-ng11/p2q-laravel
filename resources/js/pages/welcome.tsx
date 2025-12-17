import AppLogoIcon from '@/components/app-logo-icon';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { UserMenuContent } from '@/components/user-menu-content';
import {
    admin,
    architect,
    opportunity,
    project,
    quote,
} from '@/routes/dashboard';
import { type SharedData } from '@/types';
import { Head, InertiaLinkProps, Link, usePage } from '@inertiajs/react';
import { CircleUserRound } from 'lucide-react';
import { easeOut, motion } from 'motion/react';

const variants = {
    hidden: { opacity: 0, y: 30 },
    visible: (i: number) => ({
        opacity: 1,
        y: 0,
        transition: {
            delay: i * 0.2, // 0.15s between each card
            duration: 0.5,
            ease: easeOut,
        },
    }),
    hover: { scale: 1.05 },
    tap: { scale: 0.9 },
};

function FeatureCard({
    href,
    title,
    description,
    index,
}: {
    href: NonNullable<InertiaLinkProps['href']>;
    title: string;
    description: string;
    index: number;
    inertia?: boolean;
}) {
    return (
        <motion.div
            custom={index}
            initial="hidden"
            animate="visible"
            whileHover="hover"
            whileTap="tap"
            variants={variants}
            className="h-35 w-100 max-w-md rounded-md border bg-primary/10 p-6 no-underline decoration-inherit shadow-md"
        >
            <Link href={href} prefetch>
                <h2 className="mb-2 text-xl font-bold">{title}</h2>
                <p className="text-sm">{description}</p>
            </Link>
        </motion.div>
    );
}

export default function WelcomePage() {
    const { user, userProperties } = usePage<SharedData>().props.auth;
    const isAdmin = userProperties.isAdministrator;

    return (
        <>
            <Head title="Welcome" />
            <div className="flex items-center justify-between px-4 py-2">
                <a href="/" className="flex items-center gap-2 font-semibold">
                    <AppLogoIcon className="size-7" />
                    P2Q Portal
                </a>

                <DropdownMenu modal={false}>
                    <DropdownMenuTrigger asChild>
                        <Button
                            variant="ghost"
                            className="flex items-center gap-2 text-muted-foreground"
                        >
                            <CircleUserRound className="h-5 w-5" />
                            <span>{user.name}</span>
                        </Button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent
                        className="w-(--radix-dropdown-menu-trigger-width) min-w-56 rounded-lg"
                        align="end"
                    >
                        <UserMenuContent user={user} />
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>

            <div className="mx-4 mt-2 rounded-md bg-linear-to-r from-sky-500 to-blue-500 pt-20 pr-8 pb-20 pl-8 text-center text-white md:mx-20">
                <h1 className="mb-2 text-4xl">
                    Welcome to Project to Quote Portal
                </h1>
                <p className="mx-auto">
                    Your all-in-one platform for managing projects, approvals,
                    and architectural collaboration with ease and efficiency.
                </p>
            </div>

            <div className="flex flex-wrap items-center justify-center gap-6 px-4 py-6">
                {isAdmin && (
                    <FeatureCard
                        index={0}
                        href={admin()}
                        title="Admin"
                        description="Gain full control over user roles, permissions, and platform settings through the Admin Portal."
                    />
                )}

                <FeatureCard
                    index={1}
                    href={opportunity()}
                    title="Opportunities"
                    description="Create and edit the opportunities, which can be converted to projects later."
                />

                <FeatureCard
                    index={2}
                    href={project()}
                    title="Projects"
                    description="Streamline your project workflows, track progress, and stay organized with our intuitive tools."
                />

                <FeatureCard
                    index={3}
                    href={quote()}
                    title="Approval"
                    description="Easily manage approvals and ensure every step is properly authorized and documented."
                />

                <FeatureCard
                    index={4}
                    href={architect()}
                    title="Architects"
                    description="Connect and collaborate with architects seamlessly to ensure design accuracy and quality."
                />
            </div>
        </>
    );
}
