import React from "react";

export default function Trade({ annualTrend, monthlyCompare }) {
    console.log(annualTrend); // Cek data di Inspect Element > Console

    return (
        <div className="p-8">
            <h1 className="text-2xl font-bold mb-4">
                Kinerja Industri Pertekstilan
            </h1>

            {/* Contoh menampilkan data simpel */}
            <div className="grid grid-cols-2 gap-4">
                <div className="bg-white p-6 rounded shadow">
                    <h2 className="font-semibold mb-2">Total Ekspor 2025</h2>
                    <p className="text-3xl text-green-600">
                        $
                        {annualTrend
                            .find((item) => item.tipe_arus === "ekspor")
                            ?.["2025"].toLocaleString()}
                    </p>
                </div>

                <div className="bg-white p-6 rounded shadow">
                    <h2 className="font-semibold mb-2">Total Impor 2025</h2>
                    <p className="text-3xl text-red-600">
                        $
                        {annualTrend
                            .find((item) => item.tipe_arus === "impor")
                            ?.["2025"].toLocaleString()}
                    </p>
                </div>
            </div>
        </div>
    );
}
