import {
    Building2,
    Factory,
    Cpu,
    Globe,
    MapPin,
    CheckCircle2,
    Clock3,
    Wrench,
} from "lucide-react";

export default function FactorySummaryCard({ factory = {}, machine = {} }) {
    return (
        <div className="rounded-2xl border bg-white shadow-sm">
            {/* Header */}

            <div className="border-b px-6 py-5">
                <div className="flex items-center gap-3">
                    <Building2 className="h-6 w-6 text-blue-600" />

                    <div>
                        <h2 className="text-lg font-semibold">
                            Digital Factory Passport™
                        </h2>

                        <p className="text-sm text-slate-500">Live Preview</p>
                    </div>
                </div>
            </div>

            {/* Body */}

            <div className="space-y-6 p-6">
                {/* Company */}

                <div>
                    <div className="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Factory
                    </div>

                    <div className="text-lg font-semibold">
                        {factory.factory_name || "Factory Name"}
                    </div>

                    <div className="mt-1 flex items-center gap-2 text-sm text-slate-500">
                        <Factory className="h-4 w-4" />

                        {factory.factory_type || "Manufacturing"}
                    </div>
                </div>

                {/* Location */}

                <div className="rounded-xl bg-slate-50 p-4">
                    <div className="mb-3 flex items-center gap-2 font-medium">
                        <MapPin className="h-4 w-4 text-blue-600" />
                        Location
                    </div>

                    <div className="space-y-2 text-sm">
                        <div className="flex justify-between">
                            <span className="text-slate-500">Country</span>

                            <span>{factory.country || "-"}</span>
                        </div>

                        <div className="flex justify-between">
                            <span className="text-slate-500">Province</span>

                            <span>{factory.province || "-"}</span>
                        </div>

                        <div className="flex justify-between">
                            <span className="text-slate-500">City</span>

                            <span>{factory.city || "-"}</span>
                        </div>
                    </div>
                </div>

                {/* Primary Machine */}

                <div className="rounded-xl bg-slate-50 p-4">
                    <div className="mb-3 flex items-center gap-2 font-medium">
                        <Cpu className="h-4 w-4 text-blue-600" />
                        Primary Machine
                    </div>

                    <div className="space-y-2 text-sm">
                        <div className="flex justify-between">
                            <span className="text-slate-500">Category</span>

                            <span>{machine.machine_category || "-"}</span>
                        </div>

                        <div className="flex justify-between">
                            <span className="text-slate-500">Brand</span>

                            <span>{machine.machine_brand || "-"}</span>
                        </div>

                        <div className="flex justify-between">
                            <span className="text-slate-500">Model</span>

                            <span>{machine.machine_model || "-"}</span>
                        </div>

                        <div className="flex justify-between">
                            <span className="text-slate-500">Quantity</span>

                            <span>{machine.quantity || 0}</span>
                        </div>

                        <div className="flex justify-between">
                            <span className="text-slate-500">Installed</span>

                            <span>{machine.year_installed || "-"}</span>
                        </div>
                    </div>
                </div>

                {/* Manufacturing Intelligence */}

                <div className="rounded-xl border border-blue-100 bg-blue-50 p-5">
                    <div className="mb-3 font-semibold text-blue-900">
                        Manufacturing Intelligence™
                    </div>

                    <div className="space-y-2 text-sm">
                        <div className="flex items-center gap-2">
                            <CheckCircle2 className="h-4 w-4 text-green-600" />
                            Executive Dashboard™
                        </div>

                        <div className="flex items-center gap-2">
                            <CheckCircle2 className="h-4 w-4 text-green-600" />
                            Factory Passport™
                        </div>

                        <div className="flex items-center gap-2">
                            <CheckCircle2 className="h-4 w-4 text-green-600" />
                            Smart Business Matching™
                        </div>

                        <div className="flex items-center gap-2">
                            <CheckCircle2 className="h-4 w-4 text-green-600" />
                            Supply Chain Intelligence™
                        </div>
                    </div>
                </div>

                {/* Status */}

                <div className="rounded-xl border p-4">
                    <div className="mb-3 font-medium">Passport Status</div>

                    <div className="flex items-center gap-2 text-amber-600">
                        <Clock3 className="h-4 w-4" />
                        Draft
                    </div>

                    <p className="mt-2 text-xs text-slate-500">
                        Complete this onboarding to activate your Digital
                        Factory Passport.
                    </p>
                </div>

                {/* Statistics */}

                <div className="grid grid-cols-2 gap-3">
                    <div className="rounded-xl border p-4">
                        <div className="text-xs uppercase tracking-wide text-slate-400">
                            Production
                        </div>

                        <div className="mt-2 font-semibold">
                            {factory.production_lines || 0}
                        </div>

                        <div className="text-xs text-slate-500">
                            Production Lines
                        </div>
                    </div>

                    <div className="rounded-xl border p-4">
                        <div className="text-xs uppercase tracking-wide text-slate-400">
                            Machine
                        </div>

                        <div className="mt-2 font-semibold">
                            {machine.quantity || 0}
                        </div>

                        <div className="text-xs text-slate-500">Units</div>
                    </div>
                </div>

                {/* Footer */}

                <div className="rounded-xl bg-slate-900 p-5 text-white">
                    <div className="flex items-center gap-2">
                        <Wrench className="h-5 w-5 text-blue-300" />

                        <span className="font-semibold">
                            DIGESTEX Manufacturing Cloud™
                        </span>
                    </div>

                    <p className="mt-3 text-sm text-slate-300">
                        Your factory information will become the foundation for
                        Manufacturing Intelligence, Factory Verification,
                        Executive Dashboard, Smart Business Matching and Build
                        My Supply Chain™.
                    </p>
                </div>
            </div>
        </div>
    );
}
