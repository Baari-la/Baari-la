import { ArrowLeft } from "lucide-react";

export default function ComingSoon({ module }) {
    return (
        <div className="min-h-screen flex items-center justify-center">
            <div className="max-w-2xl text-center">
                <h1 className="text-5xl font-black">{module}</h1>

                <p className="mt-6 text-xl text-gray-500">
                    This module is currently under development.
                </p>

                <button
                    onClick={() => window.history.back()}
                    className="
                        mt-10
                        inline-flex
                        items-center
                        gap-2
                        rounded-xl
                        bg-yellow-500
                        px-6
                        py-3
                        font-bold
                        text-slate-900
                        hover:bg-yellow-400
                    "
                >
                    <ArrowLeft className="h-4 w-4" />
                    Back
                </button>
            </div>
        </div>
    );
}
