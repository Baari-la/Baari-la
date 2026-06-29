import { CircleCheckBig } from "lucide-react";

export default function SuccessState({
    title = "Success",

    description,
}) {
    return (
        <div className="rounded-3xl border border-emerald-200 bg-emerald-50 p-10 text-center">
            <CircleCheckBig size={48} className="mx-auto text-emerald-600" />

            <h2 className="mt-6 text-xl font-bold text-emerald-700">{title}</h2>

            {description && (
                <p className="mt-3 text-emerald-600">{description}</p>
            )}
        </div>
    );
}
