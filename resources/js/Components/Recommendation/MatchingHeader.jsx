import { Network } from "lucide-react";

export default function MatchingHeader({ title, description }) {
    return (
        <div className="flex items-center justify-between border-b border-slate-200 px-6 py-5">
            <div className="flex items-center gap-3">
                <div className="rounded-xl bg-sky-100 p-3">
                    <Network className="h-6 w-6 text-sky-600" />
                </div>

                <div>
                    <h2 className="text-xl font-bold text-slate-900">
                        {title}
                    </h2>

                    <p className="mt-1 text-sm text-slate-500">{description}</p>
                </div>
            </div>
        </div>
    );
}
