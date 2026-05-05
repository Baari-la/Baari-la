import { Link } from "@inertiajs/react";

export default function Pagination({ links }) {
    if (links.length <= 3) return null;

    return (
        <div className="flex flex-wrap gap-2">
            {links.map((link, key) =>
                link.url === null ? (
                    <div
                        key={key}
                        className="px-4 py-2 text-gray-600 text-[10px] font-black uppercase tracking-widest border border-white/5 rounded-xl"
                        dangerouslySetInnerHTML={{ __html: link.label }}
                    />
                ) : (
                    <Link
                        key={key}
                        href={link.url}
                        className={`px-4 py-2 text-[10px] font-black uppercase tracking-widest border rounded-xl transition-all duration-300 ${
                            link.active
                                ? "bg-yellow-500 border-yellow-500 text-[#0a192f] shadow-[0_0_15px_rgba(234,179,8,0.3)]"
                                : "bg-white/5 border-white/10 text-gray-400 hover:border-yellow-500/50 hover:text-yellow-500"
                        }`}
                        dangerouslySetInnerHTML={{ __html: link.label }}
                    />
                ),
            )}
        </div>
    );
}
