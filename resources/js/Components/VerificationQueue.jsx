export default function VerificationQueue({
    pendingUpdates,
    setSelectedUpdate,
}) {
    return (
        <div className="mb-12 bg-white/5 border border-white/10 rounded-[40px] overflow-hidden backdrop-blur-md">
            <div className="p-8 border-b border-white/5 flex justify-between items-center bg-white/5">
                <h3 className="text-white text-sm font-black uppercase italic tracking-widest">
                    Verification <span className="text-emerald-400">Queue</span>
                </h3>
                <span className="bg-emerald-500/20 text-emerald-400 px-4 py-1.5 rounded-full text-[8px] font-black uppercase tracking-widest animate-pulse">
                    {pendingUpdates?.length || 0} Pending Requests
                </span>
            </div>

            <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse">
                    <thead>
                        <tr className="border-b border-white/5 bg-white/5 text-[9px] font-black uppercase text-gray-500">
                            <th className="p-6">Company Name</th>
                            <th className="p-6">Member / Requester</th>
                            <th className="p-6 text-right">Audit Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {pendingUpdates && pendingUpdates.length > 0 ? (
                            pendingUpdates.map((update) => (
                                <tr
                                    key={update.id}
                                    className="border-b border-white/5 hover:bg-white/[0.02] transition-all group"
                                >
                                    <td className="p-6">
                                        <p className="text-xs font-black text-white uppercase italic">
                                            {update.company?.nama_perusahaan}
                                        </p>
                                    </td>
                                    <td className="p-6">
                                        <p className="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                            {update.user?.name}
                                        </p>
                                    </td>
                                    <td className="p-6 text-right">
                                        <button
                                            onClick={() =>
                                                setSelectedUpdate(update)
                                            }
                                            className="bg-emerald-600 text-[#0a192f] px-6 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-emerald-400 transition-all shadow-lg shadow-emerald-600/20"
                                        >
                                            <i className="fas fa-search-plus mr-2"></i>
                                            Audit Now
                                        </button>
                                    </td>
                                </tr>
                            ))
                        ) : (
                            <tr>
                                <td
                                    colSpan="3"
                                    className="p-10 text-center text-gray-500 text-[10px] font-bold uppercase italic"
                                >
                                    No pending updates at the moment.
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
