import { GitBranch } from "lucide-react";

export default function SupplyChainHeader({ supplyChain }) {
    if (!supplyChain) {
        return null;
    }

    return (
        <div className="border-b border-slate-100 px-6 py-5">
            <div className="flex items-start justify-between">
                <div className="flex items-center gap-3">
                    <div className="rounded-xl bg-emerald-100 p-3">
                        <GitBranch className="h-6 w-6 text-emerald-600" />
                    </div>

                    <div>
                        <h2 className="text-xl font-bold text-slate-900">
                            {supplyChain.title}
                        </h2>

                        <p className="mt-1 text-sm text-slate-500">
                            {supplyChain.description}
                        </p>
                    </div>
                </div>

                <div className="hidden text-right lg:block">
                    <div className="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Business Role
                    </div>

                    <div className="mt-1 text-base font-bold text-slate-900">
                        {supplyChain.role ?? "-"}
                    </div>

                    <div className="mt-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Ecosystem
                    </div>

                    <div className="mt-1 text-sm text-slate-600">
                        {supplyChain.ecosystem ?? "-"}
                    </div>
                </div>
            </div>
        </div>
    );
}
