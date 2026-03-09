export interface ArchitectType {
    id: number;
    architect_type_desc: string;
}

export interface ArchitectRep {
    id: number;
    name: string;
}

export interface Architect {
    id: number;
    architect_name: string;
    architect_type_id: number;
    architect_rep_id: number;
    class_id: string;
}

export interface ArchitectGrowth {
    total_architect: number;
    new_architect_this_month: number;
    growth_percentage: number | null;
    statement: string;
}
