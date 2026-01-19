export function FormatEmail({ email }: { email: string | undefined }) {
    return (
        <a
            href={'mailto:' + email}
            className="text-blue-500 hover:underline dark:text-blue-300"
        >
            {email}
        </a>
    );
}
