import { Database } from "lucide-react";

export default function EmptyTable({
    title = "No Records",

    description = "There is no data available.",
}) {
    return (
        <div className="flex min-h-[280px] flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50 p-10 text-center">
            <div className="rounded-full bg-white p-5 shadow-sm">
                <Database size={40} className="text-slate-400" />
            </div>

            <h3 className="mt-6 text-xl font-bold">{title}</h3>

            <p className="mt-3 max-w-md text-slate-500">{description}</p>
        </div>
    );
}
