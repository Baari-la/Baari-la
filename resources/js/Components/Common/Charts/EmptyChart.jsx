import { BarChart3 } from "lucide-react";

export default function EmptyChart({
    title = "No Chart Available",

    description = "There is currently no data available to display this chart.",
}) {
    return (
        <div
            className="
                flex
                min-h-[320px]
                flex-col
                items-center
                justify-center
                rounded-3xl
                border-2
                border-dashed
                border-slate-200
                bg-slate-50
                p-10
                text-center
            "
        >
            <div className="rounded-full bg-white p-5 shadow-sm">
                <BarChart3 size={40} className="text-slate-400" />
            </div>

            <h3 className="mt-6 text-xl font-bold text-slate-700">{title}</h3>

            <p className="mt-3 max-w-md text-sm leading-7 text-slate-500">
                {description}
            </p>
        </div>
    );
}
