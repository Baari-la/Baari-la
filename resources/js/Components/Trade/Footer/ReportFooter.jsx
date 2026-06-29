import {
    Database,
    CalendarDays,
    ShieldCheck,
    Download,
    ArrowRight,
} from "lucide-react";

export default function ReportFooter({ report = {} }) {
    return (
        <div className="rounded-3xl border border-slate-200 bg-white shadow-sm">
            {/* Header */}

            <div className="border-b border-slate-100 px-8 py-6">
                <h3 className="text-xl font-bold text-slate-900">
                    Report Information
                </h3>

                <p className="mt-2 text-sm text-slate-500">
                    Official publication of Digestex Executive Report Series.
                </p>
            </div>

            {/* Content */}

            <div className="grid gap-8 p-8 lg:grid-cols-2">
                {/* Left */}

                <div className="space-y-6">
                    <div className="flex items-start gap-4">
                        <Database size={22} className="mt-1 text-blue-600" />

                        <div>
                            <h4 className="font-semibold text-slate-900">
                                Data Sources
                            </h4>

                            <p className="mt-2 text-sm leading-7 text-slate-600">
                                Official Trade Statistics, Indonesia Customs,
                                Ministry of Trade, and Digestex Intelligence
                                Database.
                            </p>
                        </div>
                    </div>

                    <div className="flex items-start gap-4">
                        <ShieldCheck
                            size={22}
                            className="mt-1 text-emerald-600"
                        />

                        <div>
                            <h4 className="font-semibold text-slate-900">
                                AI-Assisted Analysis
                            </h4>

                            <p className="mt-2 text-sm leading-7 text-slate-600">
                                This report combines official trade statistics
                                with Digestex Intelligence Engine to provide
                                executive insights for business decision makers.
                            </p>
                        </div>
                    </div>
                </div>

                {/* Right */}

                <div className="rounded-2xl bg-slate-50 p-6">
                    <h4 className="text-lg font-bold text-slate-900">
                        Report Details
                    </h4>

                    <div className="mt-6 space-y-4 text-sm">
                        <div className="flex justify-between">
                            <span className="text-slate-500">
                                Report Number
                            </span>

                            <span className="font-semibold">
                                {report.reportNumber || "TR-2026-001"}
                            </span>
                        </div>

                        <div className="flex justify-between">
                            <span className="text-slate-500">Country</span>

                            <span className="font-semibold">
                                {report.country || "Indonesia"}
                            </span>
                        </div>

                        <div className="flex justify-between">
                            <span className="text-slate-500">
                                Reporting Period
                            </span>

                            <span className="font-semibold">
                                {report.period || "January – April 2026"}
                            </span>
                        </div>

                        <div className="flex justify-between">
                            <span className="text-slate-500">Published</span>

                            <span className="font-semibold flex items-center gap-2">
                                <CalendarDays size={14} />

                                {report.generatedAt || "Updated Today"}
                            </span>
                        </div>
                    </div>

                    <div className="mt-8 flex flex-wrap gap-3">
                        <button
                            disabled
                            className="inline-flex items-center gap-2 rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-400 cursor-not-allowed"
                        >
                            <Download size={18} />
                            PDF (Coming Soon)
                        </button>

                        <button className="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                            View Full Dashboard
                            <ArrowRight size={18} />
                        </button>
                    </div>
                </div>
            </div>

            {/* Bottom */}

            <div className="rounded-b-3xl border-t border-slate-100 bg-slate-50 px-8 py-5">
                <p className="text-center text-sm leading-7 text-slate-500">
                    © 2026 Digestex Intelligence Platform.
                    <br />
                    Executive Report Series is published to provide reliable
                    industry intelligence for the global textile ecosystem.
                </p>
            </div>
        </div>
    );
}
