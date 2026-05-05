import React from "react";

export default function PublicStockRadar({ topStocks = [] }) {
    return (
        <div className="py-20 bg-[#0a192f] overflow-hidden">
            <div className="max-w-7xl mx-auto px-6">
                <div className="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                    <div className="max-w-2xl">
                        <h4 className="text-blue-500 text-[10px] font-black uppercase tracking-[0.4em] mb-4">
                            Real-Time Inventory Radar
                        </h4>
                        <h2 className="text-white text-4xl md:text-5xl font-black uppercase italic tracking-tighter leading-none">
                            Available{" "}
                            <span className="text-blue-500">
                                National Stocks
                            </span>
                        </h2>
                    </div>
                    <div className="flex items-center gap-3 bg-white/5 border border-white/10 px-6 py-3 rounded-2xl">
                        <div className="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span className="text-gray-400 text-[9px] font-black uppercase tracking-widest italic">
                            Live Marketplace Data
                        </span>
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {topStocks.length > 0 ? (
                        topStocks.map((stock, index) => (
                            <div
                                key={index}
                                className="bg-white/5 border border-white/10 p-8 rounded-[40px] hover:border-blue-500/30 transition-all group relative overflow-hidden"
                            >
                                <div className="relative z-10">
                                    <p className="text-blue-400 text-[8px] font-black uppercase tracking-widest mb-3 italic">
                                        {stock.unit} Available
                                    </p>
                                    <h3 className="text-white text-lg font-black uppercase italic leading-tight mb-6 group-hover:text-blue-400 transition-colors">
                                        {stock.product_name}
                                    </h3>
                                    <div className="flex justify-between items-end border-t border-white/5 pt-6">
                                        <div>
                                            <p className="text-gray-500 text-[7px] font-black uppercase mb-1">
                                                Volume
                                            </p>
                                            <p className="text-white font-black italic">
                                                {stock.total_qty.toLocaleString()}
                                            </p>
                                        </div>
                                        <div className="text-right">
                                            <p className="text-emerald-500 text-[7px] font-black uppercase mb-1 italic">
                                                Verified Suppliers
                                            </p>
                                            <p className="text-emerald-500 font-black italic">
                                                {stock.total_suppliers}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div className="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-10 transition-opacity">
                                    <i className="fas fa-boxes text-8xl text-white"></i>
                                </div>
                            </div>
                        ))
                    ) : (
                        <div className="col-span-full text-center py-20 bg-white/5 rounded-[40px] border border-dashed border-white/10">
                            <p className="text-gray-500 text-xs font-bold uppercase italic tracking-widest">
                                Initializing Global Stock Radar...
                            </p>
                        </div>
                    )}
                </div>

                <div className="mt-16 text-center">
                    <p className="text-gray-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-6 italic">
                        Access detailed pricing and supplier contacts via
                        premium membership
                    </p>
                    <button className="bg-blue-600 hover:bg-blue-500 text-white px-10 py-4 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-2xl shadow-blue-600/20 transition-all">
                        Explore Full Inventory
                    </button>
                </div>
            </div>
        </div>
    );
}
