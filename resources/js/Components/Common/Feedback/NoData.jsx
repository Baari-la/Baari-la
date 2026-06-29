import { DatabaseZap } from "lucide-react";

export default function NoData({
    title = "No Data Available",

    description = "The requested dataset is currently unavailable.",
}) {
    return (
        <div className="flex min-h-[280px] flex-col items-center justify-center rounded-3xl border border-slate-200 bg-white p-8 text-center">
            <DatabaseZap size={46} className="text-slate-400" />

            <h3 className="mt-5 text-lg font-bold">{title}</h3>

            <p className="mt-3 text-slate-500">{description}</p>
        </div>
    );
}
