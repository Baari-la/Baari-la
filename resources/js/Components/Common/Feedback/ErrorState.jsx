import { TriangleAlert } from "lucide-react";

export default function ErrorState({
    title = "Something went wrong",

    description = "We couldn't load the requested information.",

    onRetry,
}) {
    return (
        <div className="rounded-3xl border border-red-200 bg-red-50 p-10 text-center">
            <TriangleAlert size={48} className="mx-auto text-red-500" />

            <h2 className="mt-6 text-xl font-bold text-red-700">{title}</h2>

            <p className="mt-3 text-red-600">{description}</p>

            {onRetry && (
                <button
                    onClick={onRetry}
                    className="mt-6 rounded-xl bg-red-600 px-5 py-3 font-semibold text-white hover:bg-red-700"
                >
                    Retry
                </button>
            )}
        </div>
    );
}
