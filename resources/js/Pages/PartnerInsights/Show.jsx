import WebsiteLayout from "@/Layouts/WebsiteLayout";
import { Link } from "@inertiajs/react";

export default function Show({ partner, featured, articles }) {
    return (
        <WebsiteLayout>
            <div className="max-w-7xl mx-auto px-6 py-24">
                <div className="text-center">
                    <span className="text-yellow-600 text-xs font-black uppercase tracking-[0.4em]">
                        PARTNER KNOWLEDGE CENTER
                    </span>

                    <h1 className="mt-4 text-5xl font-black text-[#0a192f]">
                        {partner}
                    </h1>

                    <p className="mt-6 max-w-3xl mx-auto text-slate-600">
                        Industry insights, innovation updates, compliance
                        guidance, and thought leadership from {partner}.
                    </p>
                </div>
            </div>
        </WebsiteLayout>
    );
}
