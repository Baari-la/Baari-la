import { TrendingUp } from "lucide-react";

export default function MarketHeader() {
    return (
        <div className="max-w-4xl">
            <div className="inline-flex items-center gap-2 rounded-full bg-blue-100 px-4 py-2">
                <TrendingUp size={16} className="text-blue-700" />

                <span className="text-xs font-bold uppercase tracking-[0.25em] text-blue-700">
                    Global Textile Market Intelligence
                </span>
            </div>

            <h2 className="mt-6 text-4xl font-black tracking-tight text-slate-900">
                Market Intelligence
            </h2>

            <p className="mt-5 text-lg leading-8 text-slate-600">
                <strong>EN :</strong> Real-time commodity, currency, logistics,
                and raw material intelligence for the global textile industry.
            </p>

            <p className="mt-4 border-l-4 border-blue-500 pl-5 text-base leading-7 text-slate-500">
                <strong>ID :</strong> Informasi pasar global secara real-time
                yang mengintegrasikan komoditas, nilai tukar, logistik, dan
                bahan baku untuk industri tekstil.
            </p>
        </div>
    );
}
