export default function StockOverview({ stockOverview }) {
    return (
        <div className="mb-12 bg-white/5 border border-white/10 rounded-[40px] overflow-hidden backdrop-blur-md">
            {/* HEADER */}
            <div className="p-8 border-b border-white/5 bg-white/5 flex justify-between items-center">
                <div>
                    <h3 className="text-white text-sm font-black uppercase italic tracking-widest">
                        National{" "}
                        <span className="text-blue-500">Stock Inventory</span>
                    </h3>
                    <p className="text-[9px] text-gray-500 font-bold uppercase mt-1 italic text-nowrap">
                        Aggregated Price & Material Radar
                    </p>
                </div>
                <div className="h-10 w-10 bg-blue-500/20 rounded-2xl flex items-center justify-center text-blue-500 shadow-lg">
                    <i className="fas fa-boxes"></i>
                </div>
            </div>

            {/* GRID DATA */}
            <div className="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {stockOverview.map((item, index) => (
                    <div
                        key={index}
                        className="bg-[#0d1d36] border border-white/10 p-7 rounded-[35px] relative overflow-hidden group hover:border-yellow-500/30 transition-all duration-500"
                    >
                        <div className="relative z-10">
                            {/* Baris Atas: Info Supplier & Unit */}
                            <div className="flex justify-between items-center mb-4">
                                <span className="text-[7px] font-black text-blue-400 uppercase tracking-widest bg-blue-500/10 px-3 py-1 rounded-full">
                                    {item.total_suppliers} Active Suppliers
                                </span>
                                <span className="text-[8px] font-black text-gray-500 uppercase tracking-widest">
                                    {item.unit}
                                </span>
                            </div>

                            {/* Nama Produk */}
                            <h4 className="text-white font-black text-sm uppercase truncate mb-6 italic tracking-tight">
                                {item.product_name}
                            </h4>

                            {/* Grid Detail: Stok vs Harga (Sudah termasuk Indikator Tren) */}
                            <div className="grid grid-cols-2 gap-4 border-t border-white/5 pt-5">
                                <div>
                                    <p className="text-[7px] text-gray-500 font-black uppercase mb-1 tracking-widest">
                                        Total Volume
                                    </p>
                                    <p className="text-white text-lg font-black italic tracking-tighter">
                                        {item.total_qty.toLocaleString()}
                                    </p>
                                </div>
                                <div className="text-right">
                                    <p className="text-[7px] text-yellow-500 font-black uppercase mb-1 tracking-widest italic">
                                        Avg Price Benchmark
                                    </p>
                                    <div className="flex items-center justify-end gap-2">
                                        {/* INDIKATOR TREN */}
                                        {item.trend === "up" && (
                                            <i className="fas fa-caret-up text-red-500 animate-bounce"></i>
                                        )}
                                        {item.trend === "down" && (
                                            <i className="fas fa-caret-down text-emerald-500 animate-bounce"></i>
                                        )}
                                        {item.trend === "stable" && (
                                            <i className="fas fa-minus text-gray-600 text-[8px]"></i>
                                        )}

                                        <p className="text-yellow-500 text-lg font-black italic tracking-tighter">
                                            Rp{" "}
                                            {Math.round(
                                                item.avg_price,
                                            ).toLocaleString()}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Visual Progress Bar di Bawah */}
                        <div className="absolute bottom-0 left-0 h-1 bg-gradient-to-r from-blue-500 to-yellow-500 w-full opacity-20 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                ))}
            </div>
        </div>
    );
}
