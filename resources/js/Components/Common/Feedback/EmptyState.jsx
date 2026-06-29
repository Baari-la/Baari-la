import { Inbox } from "lucide-react";

export default function EmptyState({
    title = "Nothing Here",

    description = "No content is currently available.",
}) {
    return (
        <div className="flex min-h-[300px] flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50 p-10 text-center">
            <Inbox size={50} className="text-slate-400" />

            <h2 className="mt-6 text-xl font-bold">{title}</h2>

            <p className="mt-3 max-w-lg text-slate-500">{description}</p>
        </div>
    );
}
