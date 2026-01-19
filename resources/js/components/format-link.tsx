export function FormatLink({ link }: { link: string | undefined }) {
    return (
        <a
            href={link?.startsWith('http') ? link : `https://${link}`}
            target="_blank"
            rel="noopener noreferrer"
            className="text-blue-500 hover:underline dark:text-blue-300"
        >
            {link}
        </a>
    );
}
