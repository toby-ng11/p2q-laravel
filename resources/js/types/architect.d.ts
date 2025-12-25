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
