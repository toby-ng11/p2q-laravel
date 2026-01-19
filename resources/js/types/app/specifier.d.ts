interface Specifier {
    id: number;
    first_name: string;
    last_name?: string;
    job_title?: string;
    contact?: {
        central_phone_number?: string;
        email_address?: string;
    };
}
