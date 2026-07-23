export default function PublicFooter() {
    return (
        <footer className="border-t border-white/5 bg-[#020617]">
            <div className="max-w-7xl mx-auto px-6 py-12">
                <div className="grid md:grid-cols-4 gap-8">
                    <div>
                        <h3 className="font-black text-lg">DIGESTEX</h3>

                        <p className="text-sm text-gray-400 mt-3">
                            Textile Industry Ecosystem
                        </p>
                    </div>

                    <div>
                        <h4 className="font-bold mb-3">Platform</h4>

                        <ul className="space-y-2 text-sm text-gray-400">
                            <li>Industry Directory</li>
                            <li>Sourcing Hub</li>
                            <li>Market Intelligence</li>
                        </ul>
                    </div>

                    <div>
                        <h4 className="font-bold mb-3">Resources</h4>

                        <ul className="space-y-2 text-sm text-gray-400">
                            <li>Tools</li>
                            <li>Membership</li>
                            <li>News</li>
                        </ul>
                    </div>

                    <div>
                        <h4 className="font-bold mb-3">Company</h4>

                        <ul className="space-y-2 text-sm text-gray-400">
                            <li>About</li>
                            <li>Contact</li>
                        </ul>
                    </div>
                </div>

                <div className="mt-10 pt-6 border-t border-white/5 text-xs text-gray-500">
                    © {new Date().getFullYear()} DigTex. All rights reserved.
                </div>
            </div>
        </footer>
    );
}
