import { LoaderCircle } from "lucide-react";

export default function LoadingSpinner({
    title = "Loading...",

    description = "Please wait while we prepare the data.",
}) {
    return (
        <div className="flex min-h-[300px] flex-col items-center justify-center text-center">
            <LoaderCircle size={42} className="animate-spin text-blue-600" />

            <h3 className="mt-6 text-xl font-bold text-slate-900">{title}</h3>

            <p className="mt-2 text-slate-500">{description}</p>
        </div>
    );
}
